<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if (Auth::user()->role?->name !== 'Manager') {
          return redirect()
              ->route('dashboard')
              ->with('error', 'You do not have access to this page.');
        }

        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('status', 'You must be logged in.');
        }
        return $next($request);
    }
}
