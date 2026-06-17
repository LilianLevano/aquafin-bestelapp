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
    
    public function index(): View
    {
        $accounts = User::paginate(
            20,           // perPage
            ['*'],        // columns
            'page',       // pageName,
            null,
            null
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
                    'message' => 'Gebruiker aangemaakt!',
                    'route' => route('admin.accounts.index', absolute: false)],
                422 => [
                    'message' => 'Foutieve login gegevens',
                    'route' => url()->previous()],
                500 => [
                    'message' => 'Er ging iets mis met het verzoeken voor autorisatie.',
                    'route' => url()->previous()]
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
        $roles = Role::all();
        $sites = Site::all();
        return view('accounts.edit', compact('account', 'roles', 'sites'));
    }

   /**
 * Update the specified resource in storage.
 */
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
                $user->update($validated);
            },
            [
                200 => [
                    'message' => 'Gebruiker aangepast!',
                    'route' => route('admin.accounts.index', absolute: false)],
                422 => [
                    'message' => 'Foutieve login gegevens',
                    'route' => url()->previous()],
                500 => [
                    'message' => 'Er ging iets mis met het verzoeken voor autorisatie.',
                    'route' => url()->previous()]
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    #[Override]
    public function destroy(string $id): RedirectResponse
    {
        User::destroy($id);
        return redirect()->route('admin.accounts.index')->with('status', 'Gebruiker verwijderd!');
    }
}
