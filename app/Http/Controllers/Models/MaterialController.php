<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\WebController;
use App\Models\Category;
use App\Models\Material;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Override;

/**
 * Handles CRUD operations for materials in the admin panel.
 *
 * Image files are stored on the "public" disk under the "pictures-materials" directory.
 * Only the filename (not the full path) is persisted in the "image_path" column.
 * Materials are not soft-deleted; destroy() permanently removes both the record
 * and its associated image file.
 * All mutating operations delegate execution and response handling
 * to {@see WebController::handleWithCases()}.
 */
class MaterialController extends WebController
{
    /**
     * Display a listing of all materials with their associated category.
     *
     * Eager-loads the "category" relationship to avoid N+1 queries.
     *
     * @return View
     */
    #[Override]
    public function index(): View
    {
        $materials = Material::with('category')->get();
        return view('materials.index', compact('materials'));
    }

    /**
     * Show the form for creating a new material.
     *
     * Fetches all categories to populate the category select input.
     *
     * @return View
     */
    #[Override]
    public function create(): View
    {
        $categories = Category::all();
        return view('materials.create', compact('categories'));
    }

    /**
     * Store a newly created material in storage.
     *
     * Validates all fields and uploads the image to the "public" disk
     * under the "pictures-materials" directory using the original filename.
     * Only the filename is stored in "image_path"; the "image" key is removed
     * from the validated data before the model is created.
     * On success, redirects to the material index.
     * On validation error (422) or server error (500), redirects back to the create form.
     *
     * @param Request $request The incoming HTTP request containing material form data.
     *
     * @return RedirectResponse Redirects to the material index on success,
     *                          or back to the create form on error.
     * @throws ValidationException If any required field fails validation.
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
                    'description' => ['required', 'max:255', 'min:5'],
                    'image' => ['required', 'image']
                ]);

                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $filename = $file->getClientOriginalName();
                    $file->storeAs('pictures-materials', $filename, 'public');
                    $validated['image_path'] = $filename;
                }

                unset($validated['image']);
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
     * Display the specified material.
     *
     * @param string $id The primary key of the material to display.
     *
     * @return View
     * @throws ModelNotFoundException If no material exists with the given ID.
     */
    #[Override]
    public function show(string $id): View
    {
        $material = Material::findOrFail($id);
        return view('materials.show', compact('material'));
    }

    /**
     * Show the form for editing the specified material.
     *
     * Fetches all categories to populate the category select input.
     *
     * @param string $id The primary key of the material to edit.
     *
     * @return View
     * @throws ModelNotFoundException If no material exists with the given ID.
     */
    #[Override]
    public function edit(string $id): View
    {
        $material = Material::findOrFail($id);
        $categories = Category::all();
        return view('materials.edit', compact('material', 'categories'));
    }

    /**
     * Update the specified material in storage.
     *
     * The name uniqueness check ignores the material's own current name
     * via {@see Rule::unique()->ignore()}, allowing same-name updates.
     * If a new image is uploaded, the existing image file is deleted from
     * the "public" disk before the new file is stored under "pictures-materials".
     * If no new image is provided, the existing "image_path" is preserved.
     * The "image" key is removed from the validated data before the model is updated.
     * On success, redirects to the material index.
     * On validation error (422) or server error (500), redirects back to the edit form.
     *
     * @param Request $request The incoming HTTP request containing updated material data.
     * @param string  $id      The primary key of the material to update.
     *
     * @return RedirectResponse Redirects to the material index on success,
     *                          or back to the edit form on error.
     * @throws ValidationException    If any required field fails validation.
     * @throws ModelNotFoundException If no material exists with the given ID.
     */
    #[Override]
    public function update(Request $request, string $id): RedirectResponse
    {
        return $this->handleWithCases(
            $request,
            function () use ($request, $id) {
                $material = Material::findOrFail($id);
                $validated = $request->validate([
                    'name' => ['required', Rule::unique('materials', 'name')->ignore($material->id)],
                    'category_id' => ['required', 'exists:categories,id'],
                    'description' => ['required', 'max:255', 'min:5'],
                    'image' => 'nullable|image|max:2048'
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
     * Permanently remove the specified material from storage.
     *
     * If the material has an associated image file, it is deleted from the
     * "public" disk before the database record is removed.
     * This is a hard delete — no soft-delete behavior applies.
     * On success or any error, redirects to the material index.
     *
     * @param string $id The primary key of the material to delete.
     *
     * @return RedirectResponse Redirects to the material index with a status message.
     * @throws ModelNotFoundException If no material exists with the given ID.
     */
    #[Override]
    public function destroy(string $id): RedirectResponse
    {
        $request = request();
        return $this->handleWithCases(
            $request,
            function () use ($request, $id) {
                $material = Material::findOrFail($id);
                if ($material->image_path) {
                    Storage::disk('public')->delete('pictures-materials/' . $material->image_path);
                }
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
