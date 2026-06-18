<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\WebController;
use App\Models\Role;
use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Override;

/**
 * Handles CRUD operations for user accounts in the admin panel.
 *
 * Passwords are hashed via {@see Hash::make()} before persistence.
 * Users are not soft-deleted; destroy() permanently removes the record.
 * All mutating operations delegate execution and response handling
 * to {@see WebController::handleWithCases()}.
 */
class UserController extends WebController
{
    /**
     * Display a paginated listing of all users (20 per page).
     *
     * @return View
     */
    #[Override]
    public function index(): View
    {
        $accounts = User::paginate(
            20,           // perPage
            ['*'],        // columns
            'page',       // pageName
            null,         // page
            null          // total
        );
        return view('accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new user.
     *
     * Fetches all roles and sites to populate the respective select inputs.
     *
     * @return View
     */
    #[Override]
    public function create(): View
    {
        $roles = Role::all();
        $sites = Site::all();
        return view('accounts.create', compact('roles', 'sites'));
    }

    /**
     * Store a newly created user in storage.
     *
     * Validates all fields including Belgian phone number format
     * (must start with +32 or 0, followed by 8 or 9 digits).
     * Email and phone number must be unique across all users.
     * The password is hashed via {@see Hash::make()} before the model is created.
     * The "password_confirmation" field is validated but not persisted.
     * On success, redirects to the user index.
     * On validation error (422) or server error (500), redirects back to the create form.
     *
     * @param Request $request The incoming HTTP request containing user form data.
     *
     * @return RedirectResponse Redirects to the user index on success,
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
                    'first_name' => ['required', 'max:40'],
                    'last_name' => ['required', 'max:40'],
                    'email' => ['required', 'email', 'unique:users,email'],
                    'phone_number' => ['required', 'numeric', 'unique:users', 'regex:/^(\+32|0)[0-9]{8,9}$/'],
                    'role_id' => ['required', 'exists:roles,id'],
                    'site_id' => ['required', 'exists:sites,id'],
                    'password' => ['required', 'min:8'],
                    'password_confirmation' => ['required', 'same:password']
                ]);

                $validated['password'] = Hash::make($validated['password']);
                User::create($validated);
            },
            [
                200 => [
                    'message' => 'Gebruiker succesvol aangemaakt!',
                    'route' => route('admin.accounts.index', absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('admin.accounts.create', absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('admin.accounts.create', absolute: true)]
            ]
        );
    }

    /**
     * Display the specified user.
     *
     * @param string $id The primary key of the user to display.
     *
     * @return View
     * @throws ModelNotFoundException If no user exists with the given ID.
     */
    #[Override]
    public function show(string $id): View
    {
        $account = User::findOrFail($id);
        return view('accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * Fetches all roles and sites to populate the respective select inputs.
     *
     * @param string $id The primary key of the user to edit.
     *
     * @return View
     * @throws ModelNotFoundException If no user exists with the given ID.
     */
    #[Override]
    public function edit(string $id): View
    {
        $account = User::findOrFail($id);
        $roles = Role::all();
        $sites = Site::all();
        return view('accounts.edit', compact('account', 'roles', 'sites'));
    }

    /**
     * Update the specified user in storage.
     *
     * Email and phone number uniqueness checks ignore the user's own current values
     * via Laravel's string-form ignore syntax ('unique:table,column,ignoreId').
     * Note: the phone number regex validation present in {@see store()} is absent here —
     * the format is not re-validated on update.
     * Password update is optional: if "password" is provided and matches
     * "password_confirmation", it is hashed and persisted. If "password" is absent
     * or empty, both password fields are removed from the validated data and the
     * existing password is preserved.
     * WARNING: if "password" is present but does not match "password_confirmation",
     * the plain-text password is persisted without hashing. Add a 'confirmed' validation
     * rule to prevent this.
     * On success, redirects to the user index.
     * On validation error (422) or server error (500), redirects to the user index.
     *
     * @param Request $request The incoming HTTP request containing updated user data.
     * @param string  $id      The primary key of the user to update.
     *
     * @return RedirectResponse Redirects to the user index with a status message.
     * @throws ValidationException    If any required field fails validation.
     * @throws ModelNotFoundException If no user exists with the given ID.
     */
    #[Override]
    public function update(Request $request, string $id): RedirectResponse
    {
        return $this->handleWithCases(
            $request,
            function () use ($request, $id) {
                $validated = $request->validate([
                    'first_name' => ['required', 'max:40'],
                    'last_name' => ['required', 'max:40'],
                    'email' => ['required', 'email', 'unique:users,email,' . $id],
                    'phone_number' => ['required', 'numeric', 'unique:users,phone_number,' . $id],
                    'role_id' => ['required', 'exists:roles,id'],
                    'site_id' => ['required', 'exists:sites,id'],
                    'password' => ['nullable'],
                    'password_confirmation' => ['nullable']
                ]);

                if ($validated['password']) {
                    if ($validated['password'] == $validated['password_confirmation']) {
                        $validated['password'] = Hash::make($validated['password']);
                    }
                } else {
                    unset($validated['password']);
                    unset($validated['password_confirmation']);
                }

                $user = User::findOrFail($id);
                $user->updateOrFail($validated);
            },
            [
                200 => [
                    'message' => 'Gebruiker succesvol aangepast!',
                    'route' => route('admin.accounts.index', absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('admin.accounts.index', absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('admin.accounts.index', absolute: true)]
            ]
        );
    }

    /**
     * Permanently remove the specified user from storage.
     *
     * This is a hard delete — no soft-delete behaviour applies.
     * On success or any error, redirects to the user index.
     *
     * @param string $id The primary key of the user to delete.
     *
     * @return RedirectResponse Redirects to the user index with a status message.
     * @throws ModelNotFoundException If no user exists with the given ID.
     */
    #[Override]
    public function destroy(string $id): RedirectResponse
    {
        $request = request();
        return $this->handleWithCases(
            $request,
            function () use ($request, $id) {
                $user = User::findOrFail($id);
                $user->deleteOrFail();
            },
            [
                200 => [
                    'message' => 'Gebruiker succesvol verwijderd!',
                    'route' => route('admin.accounts.index', absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('admin.accounts.index', absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('admin.accounts.index', absolute: true)]
            ]
        );
    }
}
