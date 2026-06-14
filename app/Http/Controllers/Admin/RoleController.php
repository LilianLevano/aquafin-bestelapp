<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:roles,name|max:255|min:2',
        ]);

        try{
            Role::create($validated);
            return redirect()->route('admin.roles.index')->with('status', 'Rol aangemaakt!');
        }catch (\Exception $exception){
            return redirect()->route('admin.roles.create')->with('error', $exception->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|unique:roles,name|max:255|min:2',
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
    public function destroy(string $id)
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
