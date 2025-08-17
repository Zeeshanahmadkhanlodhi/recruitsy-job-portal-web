<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function updateAccount(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['sometimes', 'string', 'max:100'],
            'last_name' => ['sometimes', 'string', 'max:100'],
            'email' => ['sometimes', 'email', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'location' => ['sometimes', 'nullable', 'string', 'max:255'],
            'avatar' => ['sometimes', 'image', 'max:2048'],
            'avatar_remove' => ['sometimes', 'boolean'],
        ]);

        $user = Auth::user();

        if ($request->filled('first_name')) {
            $user->first_name = $validated['first_name'];
        }
        if ($request->filled('last_name')) {
            $user->last_name = $validated['last_name'];
        }
        if ($request->filled('first_name') || $request->filled('last_name')) {
            $user->name = trim(($user->first_name ?? '').' '.($user->last_name ?? ''));
        }
        if ($request->filled('email')) {
            $user->email = $validated['email'];
        }
        if ($request->has('phone')) {
            $user->phone = $validated['phone'] ?? null;
        }
        if ($request->has('location')) {
            $user->location = $validated['location'] ?? null;
        }

        if ($request->boolean('avatar_remove') && $user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
            $user->avatar_path = null;
        } elseif ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_path = $path;
        }

        $user->save();

        return redirect()->back()->with('status', 'Account updated');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = $validated['new_password'];
        $user->save();

        // Invalidate other sessions on other devices
        Auth::logoutOtherDevices($validated['new_password']);

        return back()->with('status', 'Password updated successfully.');
    }
}


