<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\WebController;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Override;

class RoleController extends WebController
{
    /**
     * Display a listing of the resource.
     */
    #[Override]
    public function index(): View
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    #[Override]
    public function create(): View
    {
        return view('roles.create');
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
                    'name' => 'required|unique:roles|max:255',
                ]);
                Role::create($validated);
            },
            [
                200 => [
                    'message' => 'Rol succesvol aangemaakt!',
                    'route' => route('admin.roles.index', absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('admin.roles.create', absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('admin.roles.create', absolute: true)]
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    #[Override]
    public function edit(string $id): View
    {
        $role = Role::findOrFail($id);
        return view('roles.edit', compact('role'));
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
                    'name' => 'required|unique:roles|max:255',
                ]);

                $role = Role::findOrFail($id);
                $role->updateOrFail($validated);
            },
            [
                200 => [
                    'message' => 'Rol succesvol geüpdatet!',
                    'route' => route('admin.roles.index', absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('admin.roles.edit', ['role' => $id], absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('admin.roles.edit', ['role' => $id], absolute: true)]
            ]
        );
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
                $role = Role::findOrFail($id);
                $role->deleteOrFail();
            },
            [
                200 => [
                    'message' => 'Rol succesvol verwijderd!',
                    'route' => route('admin.roles.index', absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('admin.roles.index', absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('admin.roles.index', absolute: true)]
            ]
        );
    }
}
