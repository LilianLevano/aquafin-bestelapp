<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\WebController;
use App\Models\Order;
use App\Models\Material;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;
use Override;

class OrderController extends WebController
{
    /**
     * Display a listing of the resource.
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
     * Show the form for creating a new resource.
     */
    #[Override]
    public function create(): View
    {
        $materials = Material::select(['id', 'name', 'category_id', 'type'])->with('category:id,name')->get();
        $sites = Site::select(['id', 'description'])->get();
        return view('orders.create', compact('materials', 'sites'));
    }

    /**
     * Store a newly created resource in storage.
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
                    $pivotData[$materialId] = [
                        'quantity' => $quantities[$materialId] ?? null
                    ];
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
     * Display the specified resource.
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
     * Remove the specified resource from storage.
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
