<?php

namespace App\Http\Controllers;

use BadMethodCallException;
use Illuminate\View\View;

/**
 * WebController serves as a base controller for web-facing resource controllers.
 * It provides conventional methods for resource creation and editing forms,
 * which are intentionally not implemented here and should be overridden as needed.
 */
abstract class WebController extends ResourceController
{
    /**
     * Show the form for creating a new resource.
     *
     * @override Override this method to provide the appropriate view for creation.
     * @throws BadMethodCallException
     * @return View
     */
    public function create(): View
    {
        throw new BadMethodCallException('Non-implemented method: create().', 1);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @override Override this method to provide the appropriate view for editing.
     * @param string $id
     * @throws BadMethodCallException
     * @return View
     */
    public function edit(string $id): View
    {
        throw new BadMethodCallException('Non-implemented method: edit().', 1);
    }
}
