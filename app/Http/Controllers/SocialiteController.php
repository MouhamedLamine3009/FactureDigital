<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    private const PROVIDERS = ['google'];

    private const GOOGLE_SCOPES = 'openid email profile';

    /**
     * Redirect to the OAuth provider.
     */
    public function redirect(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        if ($provider === 'google') {
            return $this->redirectToGoogle();
        }

        abort(404);
    }

    /**
     * Handle the callback from the OAuth provider.
     */
    public function callback(string $provider, Request $request): RedirectResponse
    {
        $this->validateProvider($provider);

        if ($provider === 'google') {
            return $this->handleGoogleCallback($request);
        }

        abort(404);
    }

    private function validateProvider(string $provider): void
    {
        if (!in_array(strtolower($provider), self::PROVIDERS, true)) {
            abort(404);
        }
    }

    private function redirectToGoogle(): RedirectResponse
    {
        $clientId = config('services.google.client_id');

        if (!$clientId) {
            return redirect()->route('login')->with('error', 'La connexion Google n\'est pas encore configurée.');
        }

        $state = Str::random(40);
        session(['google_oauth_state' => $state]);

        $query = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => config('services.google.redirect'),
            'response_type' => 'code',
            'scope' => self::GOOGLE_SCOPES,
            'state' => $state,
            'prompt' => 'select_account',
            'access_type' => 'online',
            'include_granted_scopes' => 'true',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    private function handleGoogleCallback(Request $request): RedirectResponse
    {
        if ($request->filled('error')) {
            return redirect()->route('login')->with('error', 'Connexion Google annulée.');
        }

        if (!hash_equals((string) session('google_oauth_state'), (string) $request->input('state'))) {
            return redirect()->route('login')->with('error', 'Session d\'authentification invalide. Veuillez réessayer.');
        }
        session()->forget('google_oauth_state');

        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code' => $request->input('code'),
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri' => config('services.google.redirect'),
            'grant_type' => 'authorization_code',
        ]);

        if (!$tokenResponse->successful()) {
            return redirect()->route('login')->with('error', 'Erreur lors de l\'authentification. Veuillez réessayer.');
        }

        $userInfo = Http::withToken($tokenResponse->json('access_token'))
            ->get('https://www.googleapis.com/oauth2/v3/userinfo');

        if (!$userInfo->successful()) {
            return redirect()->route('login')->with('error', 'Impossible de récupérer vos informations. Veuillez réessayer.');
        }

        return $this->authenticateUser($userInfo->json());
    }

    private function authenticateUser(array $googleUser): RedirectResponse
    {
        $email = $googleUser['email'] ?? null;

        if (!$email) {
            return redirect()->route('login')->with('error', 'Aucun email associé à votre compte Google.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name' => trim(($googleUser['name'] ?? '') ?: ($googleUser['given_name'] ?? 'Utilisateur')),
                'email' => $email,
                'password' => Str::random(40),
                'email_verified_at' => now(),
            ]);

            $this->createPersonalTeam($user);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    private function createPersonalTeam(User $user): void
    {
        if ($user->ownedTeams()->exists()) {
            return;
        }

        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => 'Équipe de ' . explode(' ', $user->name, 2)[0],
            'personal_team' => true,
        ]));
    }
}