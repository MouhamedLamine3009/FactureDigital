<x-guest-layout>
    {{-- ============================================================
         SPLIT-SCREEN : Colonne gauche (branding) + droite (formulaire)
         ============================================================ --}}
    <div class="flex min-h-screen flex-col lg:flex-row">

        {{-- ========== COLONNE GAUCHE — Branding / Slogan / Stats ========== --}}
        <div class="relative flex w-full items-center justify-center overflow-hidden bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900 px-6 py-12 lg:w-1/2 lg:min-h-screen">
            {{-- Motif décoratif en arrière-plan --}}
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -left-20 -top-20 h-72 w-72 rounded-full bg-white/5 blur-3xl"></div>
                <div class="absolute -bottom-20 -right-20 h-96 w-96 rounded-full bg-accent-500/10 blur-3xl"></div>
                <div class="absolute left-1/2 top-1/3 h-64 w-64 -translate-x-1/2 rounded-full bg-white/5 blur-3xl"></div>
            </div>

            <div class="relative z-10 mx-auto max-w-md text-center text-white">
                {{-- Logo --}}
                <div class="mb-6 inline-flex items-center justify-center">
                    <span class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-white/20 backdrop-blur-sm shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8 text-white">
                            <path d="M7 3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2H7zm2 4h6v1H9V7zm0 3h6v1H9v-1zm0 3h4v1H9v-1z"/>
                            <path d="M19 8l.5-.5M20 9l.7-.7M19 12l.5.5M20 15l.7.7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none" opacity="0.7"/>
                        </svg>
                    </span>
                </div>

                <h1 class="text-3xl font-bold tracking-tight">DigiFact</h1>
                <p class="mt-2 text-lg font-light text-white/80">La facturation simple, rapide et moderne</p>

                {{-- Slogan --}}
                <p class="mt-6 text-base leading-relaxed text-white/90">
                    Créez vos devis et factures en quelques clics, suivez vos paiements et générez vos PDFs instantanément.
                </p>

                {{-- Carte stats glassmorphism --}}
                <div class="mx-auto mt-8 max-w-xs rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-md shadow-xl">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-2xl font-bold">+500</p>
                            <p class="text-xs font-medium text-white/70">factures générées chaque mois</p>
                        </div>
                    </div>
                </div>

                {{-- Lien retour accueil --}}
                <a href="{{ url('/') }}" class="mt-8 inline-flex items-center gap-1.5 text-sm font-medium text-white/70 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Retour à l'accueil
                </a>
            </div>
        </div>

        {{-- ========== COLONNE DROITE — Formulaire d'inscription ========== --}}
        <div class="flex w-full items-center justify-center bg-white px-6 py-12 lg:w-1/2 lg:min-h-screen dark:bg-slate-900">
            <div class="w-full max-w-md">

                {{-- En-tête du formulaire --}}
                <div class="mb-8 text-center lg:text-left">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Créer un compte</h2>
                    <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Commencez en quelques secondes. Aucune carte requise.</p>
                </div>

                {{-- Validation errors --}}
                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    {{-- Nom --}}
                    <div>
                        <x-label for="name" value="{{ __('Nom complet') }}" class="text-sm font-medium text-slate-700 dark:text-slate-300" />
                        <div class="relative mt-1.5">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <x-input id="name" class="block w-full rounded-xl border-slate-200 bg-slate-50 pl-11 pr-4 py-3 text-sm placeholder-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:placeholder-slate-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Votre nom" />
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <x-label for="email" value="{{ __('Email') }}" class="text-sm font-medium text-slate-700 dark:text-slate-300" />
                        <div class="relative mt-1.5">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <x-input id="email" class="block w-full rounded-xl border-slate-200 bg-slate-50 pl-11 pr-4 py-3 text-sm placeholder-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:placeholder-slate-500" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="vous@exemple.com" />
                        </div>
                    </div>

                    {{-- Mot de passe --}}
                    <div>
                        <div class="flex items-center justify-between">
                            <x-label for="password" value="{{ __('Mot de passe') }}" class="text-sm font-medium text-slate-700 dark:text-slate-300" />
                        </div>
                        <div class="relative mt-1.5">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <x-input id="password" class="block w-full rounded-xl border-slate-200 bg-slate-50 pl-11 pr-11 py-3 text-sm placeholder-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:placeholder-slate-500" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                            {{-- Œil toggle --}}
                            <button type="button" onclick="togglePasswordVisibility('password')" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                                <svg id="password-eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Confirmation mot de passe --}}
                    <div>
                        <x-label for="password_confirmation" value="{{ __('Confirmer le mot de passe') }}" class="text-sm font-medium text-slate-700 dark:text-slate-300" />
                        <div class="relative mt-1.5">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <x-input id="password_confirmation" class="block w-full rounded-xl border-slate-200 bg-slate-50 pl-11 pr-4 py-3 text-sm placeholder-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:placeholder-slate-500" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Retaper le mot de passe" />
                        </div>
                    </div>

                    {{-- CGU --}}
                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div>
                            <x-label for="terms">
                                <div class="flex items-start gap-3">
                                    <x-checkbox name="terms" id="terms" required class="mt-0.5 rounded border-slate-300 text-primary-600 focus:ring-primary-500" />
                                    <div class="text-sm text-slate-600 dark:text-slate-400">
                                        {!! __('J\'accepte les :terms_of_service et la :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="' . route('terms.show') . '" class="font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 underline">' . __('Conditions d\'utilisation') . '</a>',
                                            'privacy_policy' => '<a target="_blank" href="' . route('policy.show') . '" class="font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 underline">' . __('Politique de confidentialité') . '</a>',
                                        ]) !!}
                                    </div>
                                </div>
                            </x-label>
                        </div>
                    @endif

                    {{-- Séparateur "ou par email" --}}
                    <div class="relative py-1">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="bg-white px-3 text-slate-400 dark:bg-slate-900 dark:text-slate-500">ou par email</span>
                        </div>
                    </div>

                    {{-- Bouton S'inscrire --}}
                    <div>
                        <button type="submit" class="group inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-600 to-primary-700 px-5 py-3.5 text-sm font-semibold text-white shadow-md hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition-all duration-200">
                            {{ __('S\'inscrire') }}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                {{-- Lien connexion --}}
                <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Déjà inscrit ?') }}
                    <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 transition-colors">
                        {{ __('Connectez-vous') }}
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
                // Icône œil barré
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                `;
            } else {
                input.type = 'password';
                // Icône œil normal
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                `;
            }
        }
    </script>
</x-guest-layout>

