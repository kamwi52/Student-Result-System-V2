<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Fill the user model with validated data (name, email)
        $user->fill($request->validated());

        // If the email was changed, reset the verification status
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // === CORRECTED: HANDLE PHOTO UPLOAD ===
        if ($request->hasFile('photo')) {
            // This validation can be moved to ProfileUpdateRequest if desired
            $request->validate(['photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048']]);

            // Store the file in 'public/profile-photos' and get its path
            $path = $request->file('photo')->store('profile-photos', 'public');
            
            // Save the path to the user's `profile_photo_path` column
            $user->profile_photo_path = $path;
        }
        // ===================================

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}