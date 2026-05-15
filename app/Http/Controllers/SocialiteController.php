<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SocialiteController extends Controller
{
    /**
     * Redirect to the OAuth provider.
     */
    public function redirect($provider)
    {
        // Validate provider
        if (!in_array($provider, ['google', 'microsoft'])) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the callback from the OAuth provider.
     */
    public function callback($provider)
    {
        // Validate provider
        if (!in_array($provider, ['google', 'microsoft'])) {
            abort(404);
        }

        try {
            $user = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Erreur lors de l\'authentification. Veuillez réessayer.');
        }

        // Find or create user
        $authUser = User::updateOrCreate(
            ['email' => $user->getEmail()],
            [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'email_verified_at' => now(),
            ]
        );

        Auth::login($authUser, true);

        return redirect()->route('dashboard');
    }
}
