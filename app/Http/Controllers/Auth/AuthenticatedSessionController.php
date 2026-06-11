<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

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
        try {
            $request->authenticate();
            $request->session()->regenerate();

            return redirect()
                ->intended(route('/', absolute: false))
                ->with('status', 'success');
        } catch (ValidationException $e) {
            return redirect()
                ->intended(route('login', absolute: false))
                ->with('status', 'fail')
                ->with('message', 'Foutieve login gegevens');
        } catch (\Exception $e) {
            return redirect()
                ->intended(route('login', absolute: false))
                ->with('status', 'error')
                ->with('message', 'Er ging iets mis met het verzoeken voor autorisatie...');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
