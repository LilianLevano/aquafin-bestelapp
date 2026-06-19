<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\WebController;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Override;

/**
 * Handles CRUD operations for categories in the admin panel.
 *
 * Categories are not soft-deleted; destroy() permanently removes the record.
 * All mutating operations delegate execution and response handling
 * to {@see WebController::handleWithCases()}.
 */
class CategoryController extends WebController
{
    /**
     * Display a listing of all categories.
     *
     * @return View
     */
    #[Override]
    public function index(): View
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     *
     * @return View
     */
    #[Override]
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category in storage.
     *
     * Validates that the name is required, at least 2 characters long,
     * and unique in the categories table.
     * On success, redirects to the category index.
     * On validation error (422) or server error (500), redirects back to the create form.
     *
     * @param Request $request The incoming HTTP request containing category form data.
     *
     * @return RedirectResponse Redirects to the category index on success,
     *                          or back to the create form on error.
     * @throws ValidationException If the name fails validation or already exists.
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
     * Show the form for editing the specified category.
     *
     * @param string $id The primary key of the category to edit.
     *
     * @return View
     * @throws ModelNotFoundException If no category exists with the given ID.
     */
    #[Override]
    public function edit(string $id): View
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     *
     * Validates that the new name is required and at least 2 characters long.
     * Note: the uniqueness check does NOT currently ignore the category's own
     * existing name — renaming a category to its current name will trigger a
     * validation error. Use {@see \Illuminate\Validation\Rule::unique()->ignore()}
     * to allow same-name updates.
     * On success, redirects to the category index.
     * On validation error (422) or server error (500), redirects back to the edit form.
     *
     * @param Request $request The incoming HTTP request containing updated category data.
     * @param string  $id      The primary key of the category to update.
     *
     * @return RedirectResponse Redirects to the category index on success,
     *                          or back to the edit form on error.
     * @throws ValidationException    If the name fails validation or already exists.
     * @throws ModelNotFoundException If no category exists with the given ID.
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
     * Permanently remove the specified category from storage.
     *
     * Unlike {@see AddressController}, this controller does not use soft deletes —
     * the record is hard-deleted from the database.
     * On success or any error, redirects to the category index.
     *
     * @param string $id The primary key of the category to delete.
     *
     * @return RedirectResponse Redirects to the category index with a status message.
     * @throws ModelNotFoundException If no category exists with the given ID.
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
