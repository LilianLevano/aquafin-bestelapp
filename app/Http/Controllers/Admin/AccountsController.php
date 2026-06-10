<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
    public function create(){
        $roles = Role::all();
        $sites = Site::all();
        return view('accounts.create', compact('roles', 'sites'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'max:40'],
            'last_name' => ['required', 'max:40'],
            'email' => ['required','email','unique:users'],
            'role_id' => ['required', 'exists:roles,id'],
            'site_id' => ['required', 'exists:sites,id'],
            'password' => ['required','min:8'],
            'password_confirmation' => ['required','same:password'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);
        return redirect()->route('admin.accounts.index')->with('status', 'Gebruiker aangemaakt!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $account){
        $roles = Role::all();
        $sites = Site::all();
        return view('accounts.edit', compact('account', 'roles', 'sites'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'max:40'],
            'last_name' => ['required', 'max:40'],
            'email' => ['required','email','unique:users,email,'.$id],
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
        return redirect()->route('admin.accounts.index')->with('status', 'Gebruiker aangepast!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::destroy($id);
        return redirect()->route('admin.accounts.index')->with('status', 'Gebruiker verwijderd!');
    }
}
