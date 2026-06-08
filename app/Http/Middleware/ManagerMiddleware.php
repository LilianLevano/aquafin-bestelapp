<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManagerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!auth()->check()) {
            return redirect()->route('login')->with('status', 'You must be logged in.');
        }

        if (!auth()->user()->role->name == 'Manager') {
            return redirect()->route('home')->with('status', 'You do not have access to this page.');
        }
        return $next($request);
    }
}
