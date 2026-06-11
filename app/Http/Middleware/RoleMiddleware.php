<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param Closure(Request): (Response)  $next
     * @param string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('status', 'You must be logged in.');
        }

        if (Auth::user()->role->name !== $role) {
            return redirect()
                ->route('/')
                ->with('error', 'You do not have access to this page.');
        }
        return $next($request);
    }
}
