<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\WebController;
use App\Models\Address;
use ErrorException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Override;

class AddressController extends WebController
{
    /**
     * Display a listing of the resource.
     */
    #[Override]
    public function index(): View
    {
        $addresses = Address::all()->whereNull('deleted_at');
        return view('addresses.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    #[Override]
    public function create(): View
    {
        return view('addresses.create');
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
     * Display the specified resource.
     */
    #[Override]
    public function show(string $id): View
    {
        $address = Address::findSole($id)->whereNull('deleted_at')->firstOrFail();
        return view('addresses.show', compact('address'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    #[Override]
    public function edit(string $id): View
    {
        $address = Address::findSole($id)->whereNull('deleted_at')->firstOrFail();
        return view('addresses.edit', compact('address'));
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage (soft delete).
     * Eloquent's delete() will perform a soft delete if SoftDeletes is used
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
                    'message' => '422 validatie error.',
                    'route' => url()->previous()],
                500 => [
                    'message' => '500 error.',
                    'route' => url()->previous()]
            ],
            debug: true
        );
    }
}
