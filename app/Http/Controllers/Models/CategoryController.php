<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\WebController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Override;

class CategoryController extends WebController
{
    /**
     * Display a listing of the resource.
     */
    #[Override]
    public function index(): View
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    #[Override]
    public function create(): View
    {
        return view('categories.create');
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
                    'name' => 'required|min:2|unique:categories,name'
                ]);

                Category::create($validated);
            },
            [
                200 => [
                    'message' => 'Categorie succesvol aangemaakt!',
                    'route' => route('admin.categories.index', absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('admin.categories.create', absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('admin.categories.create', absolute: true)]
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    #[Override]
    public function edit(string $id): View
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
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
                $category = Category::findOrFail($id);
                $validated = $request->validate([
                    'name' => 'required|min:2|unique:categories,name',
                ]);

               $category->updateOrFail($validated);
            },
            [
                200 => [
                    'message' => 'Categorie succesvol geüpdatet!',
                    'route' => route('admin.categories.index', absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('admin.categories.edit', $id, absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('admin.categories.edit', $id, absolute: true)]
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
                $category = Category::findOrFail($id);
                $category->deleteOrFail();
            },
            [
                200 => [
                    'message' => 'Categorie succesvol verwijderd!',
                    'route' => route('admin.categories.index', absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('admin.categories.index', absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('admin.categories.index', absolute: true)]
            ]
        );
    }
}
