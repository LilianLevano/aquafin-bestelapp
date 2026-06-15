<?php

namespace App\Http\Controllers;

use App\Http\Controllers\WebController;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
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
        $validationRules = (new ProfileUpdateRequest())->rules();
        $validated = $request->validate($validationRules);

        $user = $request->user();
        $request->user()->fill($validated);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    #[Override]
    public function destroy(string $id): RedirectResponse
    {
        $request = request();
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = User::findOrFail($id);

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/login');
    }
}
