<?php

namespace App\Http\Controllers;

use BadMethodCallException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * This base class defines the conventional resource controller methods (index, store, show, update, destroy) as abstract or "unimplemented", so that child classes can override only those required.
 *
 * - index():   List resources (GET).
 * - store():   Store new resource (POST).
 * - show():    Show a single resource (GET).
 * - update():  Update a resource (PUT/PATCH).
 * - destroy(): Delete a resource (DELETE).
 *
 * Methods for 'create()' and 'edit()' are intentionally omitted here and should be implemented in web-facing controllers as needed (e.g., WebController).
 */
abstract class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @override Override this method to customize behavior
     * @throws BadMethodCallException
     * @return View
     */
    public function index(): View
    {
        throw new BadMethodCallException('Non-implemented method: index().', 1);
    }

    // For 'create()', see WebController if applicable.

    /**
     * Store a newly created resource in storage.
     *
     * @override Override this method to customize behavior
     * @param Request $request
     * @throws BadMethodCallException
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        throw new BadMethodCallException('Non-implemented method: store().', 1);
    }

    /**
     * Display the specified resource.
     *
     * @override Override this method to customize behavior
     * @param string $id
     * @throws BadMethodCallException
     * @return View
     */
    public function show(string $id): View
    {
        throw new BadMethodCallException('Non-implemented method: show().', 1);
    }

    // For 'edit(string $id)', see WebController if applicable.

    /**
     * Update the specified resource in storage.
     *
     * @override Override this method to customize behavior
     * @param Request $request
     * @param string $id
     * @throws BadMethodCallException
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        throw new BadMethodCallException('Non-implemented method: update().', 1);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @override Override this method to customize behavior
     * @param string $id
     * @throws BadMethodCallException
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        throw new BadMethodCallException('Non-implemented method: destroy().', 1);
    }
}
