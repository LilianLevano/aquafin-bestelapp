<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\WebController;
use App\Models\Category;
use App\Models\Material;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Override;

class MaterialController extends WebController
{
    /**
     * Display a listing of the resource.
     */
    #[Override]
    public function index(): View
    {
        $materials = Material::with('category')->get();
        return view('materials.index', compact('materials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    #[Override]
    public function create(): View
    {
        $categories = Category::all();
        return view('materials.create', compact('categories'));
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
                    'name' => ['required', 'unique:materials,name'],
                    'category_id' => ['required', 'exists:categories,id'],
                ]);
                Material::create($validated);
            },
            [
                200 => [
                    'message' => 'Materiaal succesvol aangemaakt!',
                    'route' => route('admin.materials.index', absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('admin.materials.create', absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('admin.materials.create', absolute: true)]
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    #[Override]
    public function show(string $id): View
    {
        $material = Material::findOrFail($id);
        return view('materials.show', compact('material'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    #[Override]
    public function edit(string $id): View
    {
        $material = Material::findOrFail($id);
        $categories = Category::all();
        return view('materials.edit', compact('material', 'categories'));
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
                    'name' => ['required', 'unique:materialen,name'],
                    'category_id' => ['required', 'exists:categories,id'],
                ]);

                $material = Material::findOrFail($id);
                $material->updateOrFail($validated);
            },
            [
                200 => [
                    'message' => 'Materiaal succesvol geüpdatet!',
                    'route' => route('admin.materials.index', absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('admin.materials.edit', ['material' => $id], absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('admin.materials.edit', ['material' => $id], absolute: true)]
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
            function () use ($id) {
                $material = Material::findOrFail($id);
                $material->deleteOrFail();
            },
            [
                200 => [
                    'message' => 'Materiaal succesvol verwijderd!',
                    'route' => route('admin.materials.index', absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('admin.materials.index', absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('admin.materials.index', absolute: true)]
            ]
        );
    }
}
