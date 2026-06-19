<?php

namespace App\Http\Controllers;

use App\Http\Controllers\WebController;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Override;

/**
 * Handles profile management for the currently authenticated user.
 *
 * Unlike the admin {@see UserController}, this controller operates exclusively
 * on the authenticated user's own account. The $id parameter received by each
 * method is part of the route signature but is not used for lookups —
 * the authenticated user is resolved via {@see Auth::user()} or $request->user().
 * All mutating operations delegate execution and response handling
 * to {@see WebController::handleWithCases()}.
 */
class ProfileController extends WebController
{
    /**
     * Display the authenticated user's profile edit form.
     *
     * The $id route parameter is accepted to satisfy the controller method
     * signature but is not used; the user is resolved via {@see Auth::user()}.
     *
     * @param string $id Unused route parameter.
     *
     * @return View
     */
     #[Override]
    public function edit(string $id): View
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Update the authenticated user's profile information.
     *
     * Validation is handled by {@see ProfileUpdateRequest}, which is resolved
     * via the service container (app()) rather than standard method injection,
     * so that it can be instantiated within the handleWithCases() closure.
     * The validated data is applied via fill(); "phone_number" is additionally
     * set explicitly to ensure it is updated even if absent from the fillable set.
     * If the email address is changed, "email_verified_at" is reset to null,
     * requiring the user to re-verify their email.
     * On success, validation error (422), or server error (500), redirects back
     * to the profile edit form.
     *
     * @param Request $request The incoming HTTP request containing updated profile data.
     * @param string  $id      Route parameter passed to the redirect routes; not used for lookup.
     *
     * @return RedirectResponse Redirects to the profile edit form with a status message.
     */
    #[Override]
    public function update(Request $request, string $id): RedirectResponse
    {
        return $this->handleWithCases(
            $request,
            function () use ($request, $id) {
                $profileRequest = app(ProfileUpdateRequest::class);
                $validated = $profileRequest->validated();
                $user = $request->user();
                $user->fill($validated);

                if ($user->isDirty('email')) {
                    $user->email_verified_at = null;
                }

                if ($request->filled('phone_number')) {
                    $user->phone_number = $request->input('phone_number');
                }

                $user->saveOrFail();
            },
            [
                200 => [
                    'message' => 'Profiel succesvol geüpdatet!',
                    'route' => route('profile.edit', $id, absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('profile.edit', $id, absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('profile.edit', $id, absolute: true)]
            ]
        );
    }

    /**
     * Delete the authenticated user's account.
     *
     * Validates the current password using the "userDeletion" error bag,
     * keeping deletion-related errors separate from other form errors on the page.
     * On successful validation, executes the following sequence:
     *  1. Logs the user out via {@see Auth::logout()}.
     *  2. Hard-deletes the user record via deleteOrFail().
     *  3. Invalidates the current session.
     *  4. Regenerates the CSRF token.
     * On success, redirects to the login page.
     * On validation error (422) or server error (500), redirects back to the profile edit form.
     *
     * @param string $id The primary key of the user to delete; also used for error redirect routes.
     *
     * @return RedirectResponse Redirects to the login page on success,
     *                          or back to the profile edit form on error.
     */
    #[Override]
    public function destroy(string $id): RedirectResponse
    {
        $request = request();
        return $this->handleWithCases(
            $request,
            function () use ($request, $id) {
                $request->validateWithBag('userDeletion', [
                    'password' => ['required', 'current_password'],
                ]);

                $user = User::findOrFail($id);

                Auth::logout();
                $user->deleteOrFail();

                $request->session()->invalidate();
                $request->session()->regenerateToken();
            },
            [
                200 => [
                    'message' => 'Profiel succesvol verwijderd!',
                    'route' => route('login', absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('profile.edit', $id, absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('profile.edit', $id, absolute: true)]
            ]
        );
    }
}
