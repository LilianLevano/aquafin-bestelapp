<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\WebController;
use App\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Override;

/**
 * Handles CRUD operations for roles in the admin panel.
 *
 * Roles are not soft-deleted; destroy() permanently removes the record.
 * All mutating operations delegate execution and response handling
 * to {@see WebController::handleWithCases()}.
 */
class RoleController extends WebController
{
    /**
     * Display a listing of all roles.
     *
     * @return View
     */
    #[Override]
    public function index(): View
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     *
     * @return View
     */
    #[Override]
    public function create(): View
    {
        return view('roles.create');
    }

    /**
     * Store a newly created role in storage.
     *
     * Validates that the name is required, between 2 and 255 characters,
     * and unique in the roles table.
     * On success, redirects to the role index.
     * On validation error (422) or server error (500), redirects back to the create form.
     *
     * @param Request $request The incoming HTTP request containing role form data.
     *
     * @return RedirectResponse Redirects to the role index on success,
     *                          or back to the create form on error.
     * @throws ValidationException If the name fails validation or already exists.
     */
    #[Override]
    public function store(Request $request): RedirectResponse
    {
        return $this->handleWithCases(
            $request,
            function () use ($request) {
                $validated = $request->validate([
                    'name' => 'required|unique:roles,name|max:255|min:2'
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
     * Show the form for editing the specified role.
     *
     * @param string $id The primary key of the role to edit.
     *
     * @return View
     * @throws ModelNotFoundException If no role exists with the given ID.
     */
    #[Override]
    public function edit(string $id): View
    {
        $role = Role::findOrFail($id);
        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified role in storage.
     *
     * Validates that the new name is required and between 2 and 255 characters.
     * Note: the uniqueness check does NOT currently ignore the role's own existing
     * name — updating a role without changing its name will trigger a validation error.
     * Use {@see \Illuminate\Validation\Rule::unique()->ignore()} to allow same-name updates.
     * On success, redirects to the role index.
     * On validation error (422) or server error (500), redirects back to the edit form.
     *
     * @param Request $request The incoming HTTP request containing updated role data.
     * @param string  $id      The primary key of the role to update.
     *
     * @return RedirectResponse Redirects to the role index on success,
     *                          or back to the edit form on error.
     * @throws ValidationException    If the name fails validation or already exists.
     * @throws ModelNotFoundException If no role exists with the given ID.
     */
    #[Override]
    public function update(Request $request, string $id): RedirectResponse
    {
        return $this->handleWithCases(
            $request,
            function () use ($request, $id) {
                $validated = $request->validate([
                    'name' => 'required|unique:roles,name|max:255|min:2'
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
     * Permanently remove the specified role from storage.
     *
     * This is a hard delete — no soft-delete behavior applies.
     * On success or any error, redirects to the role index.
     *
     * @param string $id The primary key of the role to delete.
     *
     * @return RedirectResponse Redirects to the role index with a status message.
     * @throws ModelNotFoundException If no role exists with the given ID.
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
