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
    public function index(): View
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
                    'message' => 'Je bent ingelogd!',
                    'route' => route('home', absolute: false)],
                422 => [
                    'message' => 'Foutieve login gegevens',
                    'route' => route('login', absolute: false)],
                500 => [
                    'message' => 'Er ging iets mis met het verzoeken voor autorisatie.',
                    'route' => route('login', absolute: false)]
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
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
