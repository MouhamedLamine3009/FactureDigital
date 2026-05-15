<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <div class="mb-5">
            <h1 class="text-xl sm:text-2xl font-semibold tracking-tight">{{ __('Connexion') }}</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                {{ __('Accédez à votre espace de facturation.') }}
            </p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" class="text-sm text-slate-700 dark:text-slate-200" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                    autofocus autocomplete="username" />
            </div>

            <div>
                <x-label for="password" value="{{ __('Mot de passe') }}" class="text-sm text-slate-700 dark:text-slate-200" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="current-password" />
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-slate-600 dark:text-slate-300">{{ __('Se souvenir de moi') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-primary-700 dark:text-primary-300 hover:underline focus:outline-none"
                        href="{{ route('password.request') }}">
                        {{ __('Mot de passe oublié ?') }}
                    </a>
                @endif
            </div>

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

            <div class="pt-2 flex items-center justify-end">
                <x-button class="w-full sm:w-auto">
                    {{ __('Se connecter') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>