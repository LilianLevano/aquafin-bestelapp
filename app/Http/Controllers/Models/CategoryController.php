<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\WebController;
use App\Models\Category;
use BadMethodCallException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        throw new BadMethodCallException('Non-implemented method.', 1);
    }

    /**
     * Show the form for creating a new resource.
     */
    #[Override]
    public function create(): View
    {
        throw new BadMethodCallException('Non-implemented method.', 1);
    }

    /**
     * Store a newly created resource in storage.
     */
    #[Override]
    public function store(Request $request): RedirectResponse
    {
        throw new BadMethodCallException('Non-implemented method.', 1);
    }

    /**
     * Display the specified resource.
     */
    #[Override]
    public function show(string $id): View
    {
        throw new BadMethodCallException('Non-implemented method.', 1);
    }

    /**
     * Show the form for editing the specified resource.
     */
    #[Override]
    public function edit(string $id): View
    {
        throw new BadMethodCallException('Non-implemented method.', 1);
    }

    /**
     * Update the specified resource in storage.
     */
    #[Override]
    public function update(Request $request, string $idy): RedirectResponse
    {
        throw new BadMethodCallException('Non-implemented method.', 1);
    }

    /**
     * Remove the specified resource from storage.
     */
    #[Override]
    public function destroy(string $id): RedirectResponse
    {
        throw new BadMethodCallException('Non-implemented method.', 1);
    }
}
