<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <div class="mb-5">
            <h1 class="text-xl sm:text-2xl font-semibold tracking-tight">{{ __('Créer un compte') }}</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ __('Commencez en quelques secondes.') }}</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Nom') }}" class="text-sm text-slate-700 dark:text-slate-200" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                    autofocus autocomplete="name" />
            </div>

            <div>
                <x-label for="email" value="{{ __('Email') }}" class="text-sm text-slate-700 dark:text-slate-200" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                    autocomplete="username" />
            </div>

            <div>
                <x-label for="password" value="{{ __('Mot de passe') }}" class="text-sm text-slate-700 dark:text-slate-200" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="new-password" />
            </div>

            <div>
                <x-label for="password_confirmation" value="{{ __('Confirmer le mot de passe') }}" class="text-sm text-slate-700 dark:text-slate-200" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div>
                    <x-label for="terms">
                        <div class="flex items-start gap-3">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="text-sm text-slate-600 dark:text-slate-300">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' => '<a target="_blank" href="' . route('terms.show') . '" class="underline text-sm text-primary-700 dark:text-primary-300 hover:opacity-80">' . __('Terms of Service') . '</a>',
                                    'privacy_policy' => '<a target="_blank" href="' . route('policy.show') . '" class="underline text-sm text-primary-700 dark:text-primary-300 hover:opacity-80">' . __('Privacy Policy') . '</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="relative py-2">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-black/10 dark:border-white/10"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="px-2 bg-white/70 dark:bg-[#0a0a0a]/60 text-slate-500 dark:text-slate-400">{{ __('Ou continuez avec') }}</span>
                </div>
            </div>

            {{-- Uncomment when Laravel/Socialite dependencies are resolved --}}
            {{-- <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="{{ url('/auth/redirect/google') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-black/10 bg-white/70 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-white dark:border-white/10 dark:bg-[#0a0a0a]/60 dark:text-slate-200">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white dark:bg-[#0a0a0a]">G</span>
                    <span>{{ __('Google') }}</span>
                </a>

                <a href="{{ url('/auth/redirect/microsoft') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-black/10 bg-white/70 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-white dark:border-white/10 dark:bg-[#0a0a0a]/60 dark:text-slate-200">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white dark:bg-[#0a0a0a]">M</span>
                    <span>{{ __('Microsoft') }}</span>
                </a>
            </div> --}}

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between pt-2">
                <a class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:underline" href="{{ route('login') }}">
                    {{ __('Déjà inscrit ?') }}
                </a>

                <x-button class="sm:ms-4">
                    {{ __('S\'inscrire') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>

