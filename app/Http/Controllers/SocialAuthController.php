<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (!$user) {
            $user = User::create([
                'first_name' => $googleUser->user['given_name'] ?? null,
                'last_name' => $googleUser->user['family_name'] ?? null,
                'name' => $googleUser->getName() ?? $googleUser->getEmail(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar_url' => $googleUser->getAvatar(),
                'password' => bin2hex(random_bytes(16)),
            ]);
        } else {
            $user->update([
                'google_id' => $user->google_id ?: $googleUser->getId(),
                'avatar_url' => $googleUser->getAvatar(),
            ]);
        }

        Auth::login($user, true);
        session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    public function redirectToLinkedIn()
    {
        // Use OpenID Connect flow which returns id_token without needing code param explicitly
        return Socialite::driver('linkedin-openid')->redirect();
    }

    public function handleLinkedInCallback()
    {
        // Handle OpenID callback
        $linkedInUser = Socialite::driver('linkedin-openid')->user();

        $user = User::where('email', $linkedInUser->getEmail())
            ->orWhere('google_id', $linkedInUser->getId()) // reuse column if you prefer separate column add it
            ->first();

        if (!$user) {
            $name = $linkedInUser->getName();
            $user = User::create([
                'first_name' => $linkedInUser->user['localizedFirstName'] ?? null,
                'last_name' => $linkedInUser->user['localizedLastName'] ?? null,
                'name' => $name ?? $linkedInUser->getEmail(),
                'email' => $linkedInUser->getEmail(),
                'google_id' => $linkedInUser->getId(),
                'avatar_url' => $linkedInUser->getAvatar(),
                'password' => bin2hex(random_bytes(16)),
            ]);
        } else {
            $user->update([
                'avatar_url' => $linkedInUser->getAvatar() ?: $user->avatar_url,
                'google_id' => $user->google_id ?: $linkedInUser->getId(),
            ]);
        }

        Auth::login($user, true);
        session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }
}


