<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-10 px-4 bg-white text-slate-900 dark:bg-[#0a0a0a] dark:text-slate-100">
    <!-- Ambient gradients -->
    <div class="pointer-events-none fixed inset-0 -z-10">
        <div class="absolute -top-40 left-1/2 h-[38rem] w-[64rem] -translate-x-1/2 rounded-full bg-indigo-500/10 blur-3xl"></div>
        <div class="absolute top-[16rem] right-[-12rem] h-[30rem] w-[30rem] rounded-full bg-accent-500/10 blur-3xl"></div>
        <div class="absolute bottom-[-12rem] left-[-12rem] h-[28rem] w-[28rem] rounded-full bg-success-500/10 blur-3xl"></div>
    </div>

    <a href="{{ url('/') }}" class="sr-only">Retour accueil</a>

    <div class="w-full sm:max-w-md">
        <div class="flex items-center justify-center">
            <div class="rounded-2xl border border-black/10 bg-white/70 p-3 shadow-sm backdrop-blur dark:border-white/10 dark:bg-[#0a0a0a]/60">
                {{ $logo }}
            </div>
        </div>

        <div class="relative mt-6 rounded-[2.1rem] border border-black/10 bg-white/70 shadow-lg backdrop-blur dark:border-white/10 dark:bg-[#0a0a0a]/60 overflow-hidden">
            <div class="absolute -inset-1 rounded-[2.3rem] bg-gradient-to-b from-primary-500/20 via-accent-500/10 to-transparent blur-2xl" aria-hidden="true"></div>

            <div class="relative p-6 sm:p-7">
                {{ $slot }}
            </div>
        </div>

        <p class="mt-4 text-center text-xs text-slate-500 dark:text-slate-400">
            {{ config('app.name', 'Facturation') }} • Accès sécurisé
        </p>
    </div>
</div>
