<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $validated = $request->validate([
                'name' => 'required|min:2|unique:categories,name',
            ]);

            Category::create($validated);
        }catch(\Exception $exception){
            return redirect()->route('admin.categories.index')->with('error', 'Er is iets mis gegaan met het maken van de categorie.');
        }

        return redirect()->route('admin.categories.index')->with('succes', 'Category created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        try{
            $validated = $request->validate([
                'name' => 'required|min:2|unique:categories,name',
            ]);

           $category->update($validated);
        }catch(\Exception $exception){
            return redirect()->route('admin.categories.index')->with('error', 'Er is iets mis gegaan met het maken van de categorie.');
        }

        return redirect()->route('admin.categories.index')->with('succes', 'Category created successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('admin.categories.index')->with('succes', 'Category deleted successfully');
    }
}
