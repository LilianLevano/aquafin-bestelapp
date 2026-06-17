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

class ProfileController extends WebController
{
    /**
     * Display the user's profile form.
     */
     #[Override]
    public function edit(string $id): View
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Update the user's profile information.
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
     * Delete the user's account.
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
