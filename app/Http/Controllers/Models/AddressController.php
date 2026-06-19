<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\WebController;
use App\Models\Address;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Override;

/**
 * Handles CRUD operations for addresses in the admin panel.
 * Supports soft deletion via Eloquent's SoftDeletes trait.
 */
class AddressController extends WebController
{
    /**
     * Display a listing of all non-deleted addresses.
     *
     * @return View
     */
    #[Override]
    public function index(): View
    {
        $addresses = Address::all()->whereNull('deleted_at');
        return view('addresses.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new address.
     *
     * @return View
     */
    #[Override]
    public function create(): View
    {
        return view('addresses.create');
    }

    /**
     * Store a newly created address in storage.
     *
     * Validates and persists a new address record. The fields "type",
     * "country_iso", and "unit_number" are optional and have no validation rules.
     * Delegates execution and response handling to {@see WebController::handleWithCases()}.
     *
     * @param Request $request The incoming HTTP request containing address form data.
     *
     * @return RedirectResponse Redirects to the address index with a status message.
     * @throws ValidationException If required fields fail validation.
     * @throws ErrorException      If an unexpected server-side error occurs.
     */
    #[Override]
    public function store(Request $request): RedirectResponse
    {
        return $this->handleWithCases(
            $request,
            function () use ($request) {
                $validated = $request->validate([
                    'type' => '',
                    'street' => 'required|string|max:255',
                    'house_number' => 'required|string|max:50',
                    'city' => 'required|string|max:100',
                    'postal_code' => 'required|string|max:20',
                    'country_iso' => '',
                    'unit_number' => ''
                ]);
                Address::create($validated);
            },
            [
                200 => [
                    'message' => 'Address created successfully.',
                    'route' => route('admin.addresses.index', absolute: false)],
                422 => [
                    'message' => 'Address created successfully.',
                    'route' => route('admin.addresses.index', absolute: false)],
                500 => [
                    'message' => 'Address created successfully.',
                    'route' => route('admin.addresses.index', absolute: false)]
            ]
        );
    }

    /**
     * Display the specified address.
     *
     * Retrieves a non-deleted address by its primary key and passes it to the show view.
     *
     * @param string $id The primary key of the address to display.
     *
     * @return View
     * @throws ModelNotFoundException If no non-deleted address exists with the given ID.
     */
    #[Override]
    public function show(string $id): View
    {
        $address = Address::findSole($id)->whereNull('deleted_at')->firstOrFail();
        return view('addresses.show', compact('address'));
    }

    /**
     * Show the form for editing the specified address.
     *
     * Retrieves a non-deleted address by its primary key and passes it to the edit view.
     *
     * @param string $id The primary key of the address to edit.
     *
     * @return View
     * @throws ModelNotFoundException If no non-deleted address exists with the given ID.
     */
    #[Override]
    public function edit(string $id): View
    {
        $address = Address::findSole($id)->whereNull('deleted_at')->firstOrFail();
        return view('addresses.edit', compact('address'));
    }

    /**
     * Update the specified address in storage.
     *
     * Validates the incoming data and applies it to the existing address record.
     * The fields "type", "country_iso", and "unit_number" are optional.
     * Delegates execution and response handling to {@see WebController::handleWithCases()}.
     *
     * @param Request $request The incoming HTTP request containing updated address data.
     * @param string  $id      The primary key of the address to update.
     *
     * @return RedirectResponse Redirects to the address index with a status message.
     * @throws ValidationException    If required fields fail validation.
     * @throws ModelNotFoundException If no non-deleted address exists with the given ID.
     * @throws ErrorException         If an unexpected server-side error occurs.
     */
    #[Override]
    public function update(Request $request, string $id): RedirectResponse
    {
        return $this->handleWithCases(
            $request,
            function () use ($request, $id) {
                $validated = $request->validate([
                    'type' => '',
                    'street' => 'required|string|max:255',
                    'house_number' => 'required|string|max:50',
                    'city' => 'required|string|max:100',
                    'postal_code' => 'required|string|max:20',
                    'country_iso' => '',
                    'unit_number' => ''
                ]);
                $address = Address::findSole($id)->whereNull('deleted_at')->firstOrFail();

                if (isset($address)) {
                    $address->fill($validated);
                    $address->save();
                }
            },
            [
                200 => [
                    'message' => 'Address updated successfully.',
                    'route' => route('admin.addresses.index', absolute: false)],
                422 => [
                    'message' => 'Address updated successfully.',
                    'route' => route('admin.addresses.index', absolute: false)],
                500 => [
                    'message' => 'Address updated successfully.',
                    'route' => route('admin.addresses.index', absolute: false)]
            ]
        );
    }

    /**
     * Soft-delete the specified address.
     *
     * Retrieves the address by its primary key (excluding already soft-deleted records)
     * and calls delete(). Because the {@see Address} model uses the SoftDeletes trait,
     * this sets the "deleted_at" timestamp instead of removing the row from the database.
     * Delegates execution and response handling to {@see WebController::handleWithCases()}.
     *
     * @param string $id The primary key of the address to soft-delete.
     *
     * @return RedirectResponse Redirects to the address index on success,
     *                          or back to the previous URL on validation/server error.
     * @throws ModelNotFoundException If no non-deleted address exists with the given ID.
     * @throws ErrorException         If an unexpected server-side error occurs.
     */
    #[Override]
    public function destroy(string $id): RedirectResponse
    {
        return $this->handleWithCases(
            request(),
            function () use ($id) {
                $address = Address::findSole($id)->whereNull('deleted_at')->firstOrFail();

                if ($address) {
                    $address->delete();
                }
            },
            [
                200 => [
                    'message' => 'Address deleted successfully.',
                    'route' => route('admin.addresses.index', absolute: false)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => url()->previous()],
                500 => [
                    'message' => 'Er ging iets intern mis, neem contact op met de IT dienst.',
                    'route' => url()->previous()]
            ],
            debug: true
        );
    }
}
