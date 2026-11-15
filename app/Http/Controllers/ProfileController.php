<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the edit form for the authenticated user's profile.
     */
    public function edit(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'staff' || $user->role === 'admin') {
            return view('management.profile-edit', compact('user'));
        }

        return view('resident.profile-edit', compact('user'));
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'contact_number' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'profile_picture' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'password' => 'nullable|confirmed|min:8',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->contact_number = $data['contact_number'] ?? null;
        $user->address = $data['address'] ?? null;
        // Upload Profile Picture
        if ($request->hasFile('profile_picture')) {

            // Delete previous profile picture
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Store new profile picture
            $user->profile_picture = $request->file('profile_picture')
                ->store('profile_pictures', 'public');
        }

        if (!empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = $request->user();
        $user->password = $request->input('password');
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully.');
    }
}
