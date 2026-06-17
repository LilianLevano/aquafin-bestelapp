<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\WebController;
use App\Models\Role;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Override;

class UserController extends WebController
{
    /**
     * Display a listing of the resource.
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
     * Show the form for creating a new resource.
     */
    #[Override]
    public function create(): View
    {
        $roles = Role::all();
        $sites = Site::all();
        return view('accounts.create', compact('roles', 'sites'));
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
                    'first_name' => ['required', 'max:40'],
                    'last_name' => ['required', 'max:40'],
                    'email' => ['required','email','unique:users'],
                    'phone_number' => ['required','numeric','unique:users'],
                    'role_id' => ['required', 'exists:roles,id'],
                    'site_id' => ['required', 'exists:sites,id'],
                    'password' => ['required','min:8'],
                    'password_confirmation' => ['required','same:password'],
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
     * Display the specified resource.
     */
    #[Override]
    public function show(string $id): View
    {
        $account = User::findOrFail($id);
        return view('accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified resource.
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
     * Update the specified resource in storage.
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
                    'email' => ['required','email','unique:users,email,'.$id],
                    'phone_number' => ['required','numeric','unique:users'],
                    'role_id' => ['required', 'exists:roles,id'],
                    'site_id' => ['required', 'exists:sites,id'],
                    'password' => ['nullable'],
                    'password_confirmation' => ['nullable'],
                ]);

                if ($validated['password']) {
                    if($validated['password'] == $validated['password_confirmation']) {
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
     * Remove the specified resource from storage.
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
