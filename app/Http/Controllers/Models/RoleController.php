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
        $validated = $request->validate([
            'name' => 'required|unique:roles|max:255',
        ]);

        try{
            Role::create($validated);
            return redirect()->route('admin.roles.index')->with('status', 'Rol aangemaakt!');
        }catch (\Exception $exception){
            return redirect()->route('admin.roles.create')->with('status', $exception->getMessage());
        }
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
        $validated = $request->validate([
            'name' => 'required|unique:roles|max:255',
        ]);

        try{
            $role = Role::findOrFail($id);
            $role->update($validated);
            return redirect()->route('admin.roles.index')->with('status', 'Rol bijgewerkt!');
        } catch (\Exception $exception){
            return redirect()->route('admin.roles.edit')->with('status', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    #[Override]
    public function destroy(string $id): RedirectResponse
    {
        try{
            $role = Role::findOrFail($id);
            $role->delete();
            return redirect()->route('admin.roles.index')->with('status', 'Rol verwijderd!');
        }catch (\Exception $exception){
            return redirect()->route('admin.roles.index')->with('status', $exception->getMessage());
        }
    }
}
