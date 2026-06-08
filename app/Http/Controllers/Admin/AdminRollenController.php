<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminRollenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('admin.rollen.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rollen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:roles|max:255',
        ]);


        try{
            Role::create($validated);
            return redirect()->route('admin.rollen.index')->with('status', 'Rol aangemaakt!');
        }catch (\Exception $exception){
            return redirect()->route('admin.rollen.create')->with('status', $exception->getMessage());
        }


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        return view('admin.rollen.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|unique:roles|max:255',
        ]);


        try{
            $role = Role::findOrFail($id);
            $role->update($validated);
            return redirect()->route('admin.rollen.index')->with('status', 'Rol bijgewerkt!');
        }catch (\Exception $exception){
            return redirect()->route('admin.rollen.edit')->with('status', $exception->getMessage());
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
            return redirect()->route('admin.rollen.index')->with('status', 'Rol verwijderd!');
        }catch (\Exception $exception){
            return redirect()->route('admin.rollen.index')->with('status', $exception->getMessage());
        }

    }
}
