<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Materiaal;
use Illuminate\Http\Request;

class MateriaalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materialen = Materiaal::with('category')->get();
        return view('materials.index', compact('materialen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('materials.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'unique:materialen,name'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);



        Materiaal::create($validated);
        return redirect()->route('admin.materials.index')->with('success', 'Materiaal is aangemaakt');


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $materiaal = Materiaal::findOrFail($id);
        return view('materials.show', compact('materiaal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
