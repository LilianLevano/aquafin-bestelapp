<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\WebController;
use App\Models\Order;
use App\Models\Material;
use App\Models\Site;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;
use Override;

/**
 * Handles CRUD operations for orders placed by technicians.
 *
 * Orders support soft deletion via Eloquent's SoftDeletes trait.
 * Materials are linked to orders via a pivot table that carries a "quantity" column.
 * Role-based access is enforced in {@see destroy()}: technicians may only cancel
 * their own orders, while managers and admins may cancel any order.
 * All mutating operations delegate execution and response handling
 * to {@see WebController::handleWithCases()}.
 */
class OrderController extends WebController
{
    /**
     * Display a listing of all orders belonging to the authenticated user,
     * sorted from most recent to oldest.
     *
     * Accepts an optional "datum" query parameter (date string) used solely
     * for formatting the error message when the query fails; it does not filter
     * the results. Defaults to today's date if not provided.
     * Eager-loads the "user", "materials", and "site" relations.
     * On failure, logs the exception and returns the index view with an error message.
     *
     * @return View
     */
    #[Override]
    public function index(): View
    {
        $date = request()->query('datum', Carbon::today()->toDateString());

        try {
            $orders = Order::with(['user', 'materials', 'site'])
                ->where('user_id', Auth::id())
                ->orderByDesc('created_at')
                ->get();

            return view('orders.index', compact('orders'));
        } catch (Exception $e) {
            Log::error('Fout bij ophalen bestellingen: ' . $e->getMessage());
            $dateFormatted = Carbon::parse($date)->format('d/m/Y');

            return view('orders.index')
                ->with('success', false)
                ->with('message', "Er ging iets mis met het ophalen van de bestellingen van {$dateFormatted}");
        }
    }

    /**
     * Show the form for creating a new order.
     *
     * Fetches all materials (id, name, category_id, type) with their category (id, name)
     * and all sites (id, description) to populate the form selects.
     * Only the necessary columns are selected to limit memory usage.
     *
     * @return View
     */
    #[Override]
    public function create(): View
    {
        $materials = Material::select(['id', 'name', 'category_id', 'type'])->with('category:id,name')->get();
        $sites = Site::select(['id', 'description'])->get();
        return view('orders.create', compact('materials', 'sites'));
    }

    /**
     * Store a newly created order in storage.
     *
     * Builds the pivot data array from the submitted "materials" and "quantity" inputs:
     * each selected material ID is mapped to its quantity, and entries with a quantity
     * of 0 or less are skipped. The "materials" and "quantity" keys are removed from
     * the validated data before the order is created, and the authenticated user's ID
     * is injected as "user_id". After creation, materials are attached via sync().
     * On success, redirects to the technician order index.
     * On validation error (422) or server error (500), redirects back to the create form.
     *
     * @param Request $request The incoming HTTP request containing order form data.
     *
     * @return RedirectResponse Redirects to the technician order index on success,
     *                          or back to the create form on error.
     * @throws ValidationException If any required field fails validation.
     */
    #[Override]
    public function store(Request $request): RedirectResponse
    {
        return $this->handleWithCases(
            $request,
            function () use ($request) {
                $validated = $request->validate([
                    'materials' => ['nullable', 'array'],
                    'quantity' => ['nullable', 'array'],
                    'delivery_date' => ['required', 'date', 'after:today'],
                    'site_id' => ['required', 'exists:sites,id'],
                ]);

                $pivotData = [];
                $materials = $request->input('materials', []);
                $quantities = $request->input('quantity', []);

                foreach ($materials as $materialId) {
                    $qty = $quantities[$materialId] ?? 0;
                    if ($qty <= 0) continue; // skip
                    $pivotData[$materialId] = ['quantity' => $qty];
                }

                unset($validated['materials'], $validated['quantity']);
                $validated['user_id'] = Auth::id();

                $order = Order::create($validated);
                $order->materials()->sync($pivotData);
            },
            [
                200 => [
                    'message' => 'Bestelling succesvol aangemaakt!',
                    'route' => route('technieker.orders.index', absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('technieker.orders.create', absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('technieker.orders.create', absolute: true)]
            ]
        );
    }

    /**
     * Display the details of the specified order,
     * including its materials (with pivot quantities) and associated site.
     *
     * Uses find() instead of findOrFail() to handle the missing-record case manually:
     * if no order is found, the index view is returned with an error message instead
     * of throwing a ModelNotFoundException.
     * On unexpected exception, logs the error and returns the index view with an error message.
     *
     * @param string $id The primary key of the order to display.
     *
     * @return View
     */
    #[Override]
    public function show(string $id): View
    {
        try {
            $order = Order::with(['materials', 'site'])->find($id);

            if (!$order) {
                return view('orders.show')
                    ->with('success', false)
                    ->with('message', "Bestelling #{$id} werd niet gevonden.");
            }

            return view('orders.show', compact(['order']));
        } catch (Exception $e) {
            Log::error('Fout bij ophalen bestelling detail: ' . $e->getMessage());

            return view('orders.index')
                ->with('success', false)
                ->with('message', 'Er ging iets mis met het ophalen van de bestellingsdetails.');
        }
    }

    /**
     * Soft-delete (cancel) the specified order.
     *
     * Enforces role-based ownership: if the authenticated user has the "Technieker" role,
     * they may only cancel their own orders. Attempting to cancel another user's order
     * throws an Exception, which is caught by handleWithCases() and returned as a 500 response.
     * Managers and admins are not subject to this restriction.
     * Because the {@see Order} model uses the SoftDeletes trait, deleteOrFail() sets
     * the "deleted_at" timestamp rather than removing the row from the database.
     * On success or any error, redirects to the manager order index.
     *
     * @param string $id The primary key of the order to cancel.
     *
     * @return RedirectResponse Redirects to the manager order index with a status message.
     * @throws ModelNotFoundException If no order exists with the given ID.
     * @throws Exception              If the authenticated technician does not own the order.
     */
    #[Override]
    public function destroy(string $id): RedirectResponse
    {
        $request = request();
        return $this->handleWithCases(
            $request,
            function () use ($request, $id) {
                $order = Order::findOrFail($id);

                // Technieker can only cancel their own orders
                if (Auth::user()->role->name === 'Technieker' && $order->user_id !== Auth::id()) {
                    throw new Exception('U heeft geen toegang om deze bestelling te verwijderen.');
                }

                $order->deleteOrFail();
            },
            [
                200 => [
                    'message' => 'Bestelling succesvol verwijderd!',
                    'route' => route('manager.orders.index', absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('manager.orders.index', absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('manager.orders.index', absolute: true)]
            ]
        );
    }
}
