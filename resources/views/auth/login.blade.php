{{-- ============================================================
    DigiFact — Page de Connexion (Split-Screen)
    ============================================================
    Colonne gauche : Dégradé + Logo + Slogan + Carte stats
    Colonne droite : Formulaire de connexion modernisé
    Mobile : Gauche → bandeau haut, droite → formulaire plein écran
    ============================================================ --}}
<x-guest-layout>

    {{-- Conteneur split-screen : flex column sur mobile, row sur desktop --}}
    <div class="flex min-h-screen flex-col lg:flex-row">

        {{-- ========== COLONNE GAUCHE (branding / dégradé) ========== --}}
        {{-- Sur mobile : visible mais réduit. Sur desktop : 50% --}}
        <div class="relative flex w-full flex-col items-center justify-center overflow-hidden bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900 px-6 py-16 text-white lg:w-1/2 lg:min-h-screen">

            {{-- Cercles décoratifs flous en arrière-plan --}}
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -left-20 -top-20 h-72 w-72 rounded-full bg-primary-400/20 blur-3xl"></div>
                <div class="absolute -bottom-16 -right-16 h-64 w-64 rounded-full bg-accent-400/20 blur-3xl"></div>
                <div class="absolute left-1/2 top-1/2 h-96 w-96 -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/5 blur-3xl"></div>
            </div>

            {{-- Contenu centré --}}
            <div class="relative z-10 mx-auto flex max-w-sm flex-col items-center text-center">

                {{-- Logo DigiFact --}}
                <div class="mb-6 inline-flex items-center justify-center">
                    <span class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-white/20 text-white shadow-lg backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8">
                            <path d="M7 3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2H7zm2 4h6v1H9V7zm0 3h6v1H9v-1zm0 3h4v1H9v-1z"/>
                            <path d="M19 8l.5-.5M20 9l.7-.7M19 12l.5.5M20 15l.7-.7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none" opacity="0.7"/>
                        </svg>
                    </span>
                </div>

                {{-- Nom de la marque --}}
                <h2 class="text-2xl font-bold tracking-tight">DigiFact</h2>

                {{-- Slogan --}}
                <p class="mt-3 text-lg font-light text-white/80">La facturation simple, rapide et moderne</p>
                <p class="mt-4 text-base leading-relaxed text-white/90">
                    Pour les entrepreneurs et PME sénégalaises qui veulent gagner du temps.
                </p>

                {{-- Carte stats / avantage (glassmorphism) --}}
                <div class="mt-8 w-full rounded-2xl border border-white/20 bg-white/10 p-5 shadow-xl backdrop-blur-md">
                    <div class="flex items-center gap-4">
                        {{-- Icône stats (graphique) --}}
                        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-2xl font-bold leading-none">+500</p>
                            <p class="mt-1 text-sm font-medium text-white/70">factures générées chaque mois</p>
                        </div>
                    </div>

                    {{-- Barre de progression décorative --}}
                    <div class="mt-4 h-1.5 w-full rounded-full bg-white/20">
                        <div class="h-1.5 w-3/4 rounded-full bg-white/40"></div>
                    </div>
                    <p class="mt-2 text-xs text-white/60">Devis, factures, paiements et PDF — tout-en-un</p>
                </div>
            </div>

            {{-- Footer / lien retour --}}
            <a href="{{ url('/') }}" class="mt-10 inline-flex items-center gap-1.5 text-sm font-medium text-white/60 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Retour à l'accueil
            </a>
        </div>

        {{-- ========== COLONNE DROITE (formulaire) ========== --}}
        <div class="flex w-full items-center justify-center bg-white px-6 py-10 md:w-1/2 md:px-12 lg:px-20">

            <div class="w-full max-w-md">

                {{-- Logo simplifié (visible seulement sur mobile, car la colonne gauche disparaît) --}}
                <div class="mb-8 flex flex-col items-center md:hidden">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 text-white shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-7 w-7">
                            <path d="M7 3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2H7zm2 4h6v1H9V7zm0 3h6v1H9v-1zm0 3h4v1H9v-1z"/>
                            <path d="M19 8l.5-.5M20 9l.7-.7M19 12l.5.5M20 15l.7-.7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none" opacity="0.7"/>
                        </svg>
                    </span>
                    <h2 class="mt-3 text-xl font-bold text-slate-900">{{ config('app.name', 'DigiFact') }}</h2>
                </div>

                {{-- Titre du formulaire --}}
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 md:text-3xl">{{ __('Connexion') }}</h1>
                <p class="mt-2 text-sm text-slate-500">{{ __('Accédez à votre espace de facturation.') }}</p>

                {{-- Messages d'erreur --}}
                <x-validation-errors class="mb-4" />

                {{-- Statut (ex: après réinitialisation de mot de passe) --}}
                @session('status')
                    <div class="mb-4 rounded-lg bg-success-50 p-4 text-sm font-medium text-success-700">
                        {{ $value }}
                    </div>
                @endsession

                {{-- Formulaire --}}
                <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
                    @csrf

                    {{-- Champ Email --}}
                    <div>
                        <x-label for="email" value="{{ __('Email') }}" class="text-sm font-medium text-slate-700" />
                        <div class="relative mt-1">
                            {{-- Icône enveloppe --}}
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-slate-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <x-input
                                id="email"
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 pl-10 pr-4 py-3 text-sm placeholder:text-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                                type="email"
                                name="email"
                                :value="old('email')"
                                placeholder="exemple@email.com"
                                required
                                autofocus
                                autocomplete="username"
                            />
                        </div>
                    </div>

                    {{-- Champ Mot de passe --}}
                    <div>
                        <div class="flex items-center justify-between">
                            <x-label for="password" value="{{ __('Mot de passe') }}" class="text-sm font-medium text-slate-700" />
                            @if (Route::has('password.request'))
                                <a class="text-xs font-medium text-primary-600 hover:text-primary-700 hover:underline" href="{{ route('password.request') }}">
                                    {{ __('Oublié ?') }}
                                </a>
                            @endif
                        </div>

                        <div class="relative mt-1">
                            {{-- Icône cadenas --}}
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-slate-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <x-input
                                id="password"
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 pl-10 pr-11 py-3 text-sm placeholder:text-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                                type="password"
                                name="password"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password"
                            />
                            {{-- Œil toggle --}}
                            <button type="button" onclick="togglePasswordVisibility('password')" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition-colors">
                                <svg id="password-eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Checkbox "Se souvenir de moi" + séparateur "ou par email" --}}
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="flex items-center gap-2">
                            <x-checkbox id="remember_me" name="remember" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500" />
                            <span class="text-sm text-slate-600">{{ __('Se souvenir de moi') }}</span>
                        </label>
                    </div>

                    {{-- Séparateur "ou par email" --}}
                    <div class="relative py-1">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="bg-white px-3 text-slate-400">ou par email</span>
                        </div>
                    </div>

                    {{-- Bouton Se connecter --}}
                    <div>
                        <button type="submit" class="group inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-600 to-primary-700 px-5 py-3.5 text-sm font-semibold text-white shadow-md hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200">
                            {{ __('Se connecter') }}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                {{-- Lien vers inscription --}}
                <p class="mt-6 text-center text-sm text-slate-500">
                    {{ __("Pas encore de compte ?") }}
                    <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-700 transition-colors">
                        {{ __("S'inscrire") }}
                    </a>
                </p>
            </div>
        </div>
    </div>

    {{-- JavaScript : Toggle œil mot de passe --}}
    <script>
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '-eye-icon');
            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                `;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                `;
            }
        }
    </script>

</x-guest-layout>

