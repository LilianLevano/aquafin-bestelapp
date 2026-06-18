<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restricts route access based on the authenticated user's role.
 *
 * Unauthenticated users are redirected to the login page.
 * Authenticated users whose role name does not exactly match the required role
 * are redirected to the homepage. Role comparison is case-sensitive and relies
 * on the "role" Eloquent relation being loaded on the user model.
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Checks authentication first; if the user is not logged in, redirects to log in.
     * Then compares the authenticated user's role name (via the "role" relation)
     * against the required $role string. If they do not match exactly, redirects
     * to the homepage with an error message.
     * If both checks pass, forwards the request to the next middleware or controller.
     *
     * Note: if the authenticated user has no associated role, accessing ->role->name
     * will throw an error. Ensure every user has a role assigned before this middleware runs.
     *
     * @param Request                     $request The incoming HTTP request.
     * @param Closure(Request): Response  $next    The next middleware or controller in the pipeline.
     * @param string                      $role    The required role name (e.g. "Admin", "Technieker").
     *
     * @return Response
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
                ->route('home')
                ->with('error', 'You do not have access to this page.');
        }
        return $next($request);
    }
}
