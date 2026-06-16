<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materials = Material::with('category')->get();
        return view('materials.index', compact('materials'));
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

        try{
            $validated = $request->validate([
                'name' => ['required', 'unique:materials,name'],
                'category_id' => ['required', 'exists:categories,id'],
                'description' => ['required', 'max:255', 'min:5'],
                'image' => ['required', 'image'],
            ]);


            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = $file->getClientOriginalName();
                $file->storeAs('pictures-materials', $filename, 'public');
                $validated['image_path'] = $filename;
            }

            unset($validated['image']);

            Material::create($validated);
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('admin.materials.index')->with('success', 'Materiaal is aangemaakt');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $material = Material::findOrFail($id);
        return view('materials.show', compact('material'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $material = Material::findOrFail($id);
        $categories = Category::all();
        return view('materials.edit', compact('material', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        try{
            $material = Material::findOrFail($id);

            $validated = $request->validate([
                'name' => ['required', Rule::unique('materials', 'name')->ignore($material->id)],
                'category_id' => ['required', 'exists:categories,id'],
                'description' => ['required', 'max:255', 'min:5'],
                'image' => 'nullable|image|max:2048',
            ]);

            if ($request->hasFile('image')) {
                // Oude afbeelding verwijderen
                if ($material->image_path) {
                    Storage::disk('public')->delete('pictures-materials/' . $material->image_path);
                }
                $file = $request->file('image');
                $filename = $file->getClientOriginalName();
                $file->storeAs('pictures-materials', $filename, 'public');
                $validated['image_path'] = $filename;
            }

            unset($validated['image']);
            $material->update($validated);
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('admin.materials.index')->with('success', 'Materiaal is aangepast');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $material = Material::findOrFail($id);
        if ($material->image_path) {
            Storage::disk('public')->delete('pictures-materials/' . $material->image_path);
        }
        $material->delete();
        return redirect()->route('admin.materials.index')->with('success', 'Materiaal is verwijderd');
    }
}
