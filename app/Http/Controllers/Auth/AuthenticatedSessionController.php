<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        return $this->handleWithCases(
            $request,
            function () use ($request) {
                $request->authenticate();
                $request->session()->regenerate();
            },
            [
                200 => [
                    'message' => 'U bent ingelogd!',
                    'route' => route('home', absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('login', absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('login', absolute: true)]
            ]
        );
    }

    /**
     * Destroy an authenticated session.
     * Logs out the user, invalidates the session, and regenerates the CSRF token.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        return $this->handleWithCases(
            $request,
            function () use ($request) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            },
            [
                200 => [
                    'message' => 'U bent uitgelogd!',
                    'route' => route('login', absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('home', absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('home', absolute: true)]
            ]
        );
    }
}
