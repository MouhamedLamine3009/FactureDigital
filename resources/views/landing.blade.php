<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Facturation') }}</title>

    <meta name="description" content="DigiFact : La solution de facturation moderne pour démarrer et gérer vos devis, factures, paiements et PDF en quelques clics." />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html { color-scheme: light dark; }
    </style>
</head>
<body class="min-h-screen antialiased bg-white text-slate-900 dark:bg-[#0a0a0a] dark:text-slate-100">
    <!-- Ambient gradients -->
    <div class="pointer-events-none fixed inset-0 -z-10">
        <div class="absolute -top-40 left-1/2 h-[38rem] w-[64rem] -translate-x-1/2 rounded-full bg-indigo-500/10 blur-3xl"></div>
        <div class="absolute top-[16rem] right-[-12rem] h-[30rem] w-[30rem] rounded-full bg-accent-500/10 blur-3xl"></div>
        <div class="absolute bottom-[-12rem] left-[-12rem] h-[28rem] w-[28rem] rounded-full bg-success-500/10 blur-3xl"></div>
    </div>

    <a
        href="#main"
        class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[60] focus:rounded-lg focus:bg-white focus:px-3 focus:py-2 focus:text-sm focus:shadow dark:focus:bg-[#0a0a0a] dark:focus:text-slate-100"
    >
        Aller au contenu
    </a>

    <header class="relative">
        <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
            <a href="{{ url('/') }}" class="flex items-center gap-3">

                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 text-white shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                        <!-- Facture -->
                        <path d="M7 3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2H7zm2 4h6v1H9V7zm0 3h6v1H9v-1zm0 3h4v1H9v-1z"/>
                        <!-- Ondes numériques -->
                        <path d="M19 8l.5-.5M20 9l.7-.7M19 12l.5.5M20 15l.7.7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none" opacity="0.7"/>
                    </svg>
                </span>
                <span class="text-sm font-semibold tracking-tight sm:text-[15px]">{{ config('app.name', 'Facturation') }}</span>
            </a>

            <div class="hidden items-center gap-6 lg:flex" aria-label="Navigation principale">
                <a href="#main" class="text-sm font-medium text-slate-700 hover:text-primary-600 dark:text-slate-200 dark:hover:text-primary-300 transition">Accueil</a>
                <a href="#about" class="text-sm font-medium text-slate-700 hover:text-primary-600 dark:text-slate-200 dark:hover:text-primary-300 transition">À propos</a>
                <a href="#contact" class="text-sm font-medium text-slate-700 hover:text-primary-600 dark:text-slate-200 dark:hover:text-primary-300 transition">Contact</a>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" class="lg:hidden rounded-xl border border-black/10 bg-white px-3 py-2 text-sm font-medium shadow-sm transition hover:bg-black/5 focus:outline-none focus:ring-2 focus:ring-primary-500/40 dark:border-white/10 dark:bg-[#0a0a0a] dark:hover:bg-white/5" onclick="document.getElementById('mobile-nav').classList.toggle('hidden')">
                    Menu
                </button>

                <div id="mobile-nav" class="hidden flex-col gap-2 lg:hidden">
                    <a href="#main" class="rounded-xl border border-black/10 bg-white px-4 py-2 text-sm font-medium shadow-sm transition hover:bg-black/5 focus:outline-none focus:ring-2 focus:ring-primary-500/40 dark:border-white/10 dark:bg-[#0a0a0a] dark:hover:bg-white/5">Accueil</a>
                    <a href="#about" class="rounded-xl border border-black/10 bg-white px-4 py-2 text-sm font-medium shadow-sm transition hover:bg-black/5 focus:outline-none focus:ring-2 focus:ring-primary-500/40 dark:border-white/10 dark:bg-[#0a0a0a] dark:hover:bg-white/5">À propos</a>
                    <a href="#contact" class="rounded-xl border border-black/10 bg-white px-4 py-2 text-sm font-medium shadow-sm transition hover:bg-black/5 focus:outline-none focus:ring-2 focus:ring-primary-500/40 dark:border-white/10 dark:bg-[#0a0a0a] dark:hover:bg-white/5">Contact</a>
                </div>

                @if (Route::has('login'))
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="rounded-xl border border-black/10 bg-white px-4 py-2 text-sm font-medium shadow-sm transition hover:bg-black/5 focus:outline-none focus:ring-2 focus:ring-primary-500/40 dark:border-white/10 dark:bg-[#0a0a0a] dark:hover:bg-white/5"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="rounded-xl border border-black/10 bg-white px-4 py-2 text-sm font-medium shadow-sm transition hover:bg-black/5 focus:outline-none focus:ring-2 focus:ring-primary-500/40 dark:border-white/10 dark:bg-[#0a0a0a] dark:hover:bg-white/5"
                        >
                            Connexion
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-600/40"
                            >
                                Créer un compte
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>

        <!-- Hero (Accueil) -->
        <main id="main" class="mx-auto max-w-6xl px-4 pb-10">

            <div class="grid items-center gap-10 lg:grid-cols-2">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white/70 px-3 py-1 text-xs font-medium shadow-sm backdrop-blur dark:border-white/10 dark:bg-[#0a0a0a]/60">
                        <span class="inline-flex h-2 w-2 rounded-full bg-success-500"></span>
                        <span>100% Gratuit • Devis • Factures • Paiements • PDF</span>
                    </div>

                    <h1 class="mt-6 text-4xl font-semibold tracking-tight md:text-5xl">
                        DigiFact : Votre Facturation Digitale, Sans Effort
                    </h1>

                    <p class="mt-4 max-w-xl text-base leading-relaxed text-slate-600 dark:text-slate-300">
                        Avec DigiFact, créez vos devis et factures en quelques clics, suivez vos paiements et générez vos PDFs instantanément. Gratuit, simple et moderne, conçu pour les entrepreneurs et PME sénégalaises.
                    </p>

                    <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:items-center">
                        @if (Route::has('login'))
                            @auth
                                <a
                                    href="{{ url('/dashboard') }}"
                                    class="inline-flex items-center justify-center rounded-xl bg-primary-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-600/40"
                                >
                                    Aller au dashboard
                                </a>
                            @else
                                <a
                                    href="{{ route('register') }}"
                                    class="inline-flex items-center justify-center rounded-xl bg-primary-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-600/40"
                                >
                                    Démarrer maintenant
                                </a>
                                <a
                                    href="{{ route('login') }}"
                                    class="inline-flex items-center justify-center rounded-xl border border-black/10 bg-white px-5 py-3 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-black/5 focus:outline-none focus:ring-2 focus:ring-primary-500/30 dark:border-white/10 dark:bg-[#0a0a0a] dark:text-slate-100 dark:hover:bg-white/5"
                                >
                                    Se connecter
                                </a>
                            @endauth
                        @endif
                    </div>

                    <dl class="mt-8 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl border border-black/10 bg-white/70 p-4 shadow-sm backdrop-blur dark:border-white/10 dark:bg-[#0a0a0a]/60">
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400">Temps gagnés</dt>
                            <dd class="mt-1 text-lg font-semibold">Jusqu’à 80%</dd>
                        </div>
                        <div class="rounded-2xl border border-black/10 bg-white/70 p-4 shadow-sm backdrop-blur dark:border-white/10 dark:bg-[#0a0a0a]/60">
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400">Flux complet</dt>
                            <dd class="mt-1 text-lg font-semibold">Devis → Facture</dd>
                        </div>
                        <div class="rounded-2xl border border-black/10 bg-white/70 p-4 shadow-sm backdrop-blur dark:border-white/10 dark:bg-[#0a0a0a]/60">
                            <dt class="text-xs font-medium text-slate-500 dark:text-slate-400">PDF prêt</dt>
                            <dd class="mt-1 text-lg font-semibold">Téléchargement</dd>
                        </div>
                    </dl>
                </div>

                <div class="relative">
                    <div class="absolute -inset-1 rounded-[2.1rem] bg-gradient-to-b from-primary-500/25 via-accent-500/10 to-transparent blur-2xl"></div>

                    <div class="relative rounded-[2.1rem] border border-black/10 bg-white/70 p-3 shadow-lg backdrop-blur dark:border-white/10 dark:bg-[#0a0a0a]/60">
                        <div class="rounded-[1.6rem] bg-slate-50 p-5 dark:bg-[#0f0f0f]">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2" aria-hidden="true">
                                    <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>
                                    <span class="h-2.5 w-2.5 rounded-full bg-yellow-400"></span>
                                    <span class="h-2.5 w-2.5 rounded-full bg-green-400"></span>
                                </div>
                                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Aperçu</span>
                            </div>

                            <div class="mt-4 grid gap-3">
                                <div class="rounded-2xl border border-black/10 bg-white p-4 dark:border-white/10 dark:bg-[#0a0a0a]">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold">Facture #INV-1023</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Émise aujourd’hui</p>
                                        </div>
                                        <span class="rounded-full bg-success-500/15 px-3 py-1 text-xs font-semibold text-success-700 dark:text-success-300">
                                            Payée
                                        </span>
                                    </div>
                                    <div class="mt-3 h-2 w-full rounded-full bg-slate-200 dark:bg-slate-800">
                                        <div class="h-2 w-[78%] rounded-full bg-success-500"></div>
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-black/10 bg-white p-4 dark:border-white/10 dark:bg-[#0a0a0a]">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold">Devis #QUO-0442</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">À relancer</p>
                                        </div>
                                        <span class="rounded-full bg-warning-500/15 px-3 py-1 text-xs font-semibold text-warning-700 dark:text-warning-300">
                                            En attente
                                        </span>
                                    </div>
                                    <div class="mt-3 h-2 w-full rounded-full bg-slate-200 dark:bg-slate-800">
                                        <div class="h-2 w-[36%] rounded-full bg-warning-500"></div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="rounded-2xl border border-black/10 bg-white p-4 dark:border-white/10 dark:bg-[#0a0a0a]">
                                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400">CA ce mois</p>
                                        <p class="mt-1 text-lg font-semibold">25 400 XOF</p>
                                    </div>
                                    <div class="rounded-2xl border border-black/10 bg-white p-4 dark:border-white/10 dark:bg-[#0a0a0a]">
                                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Retards</p>
                                        <p class="mt-1 text-lg font-semibold">2</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 rounded-[1.4rem] border border-black/10 bg-white p-4 dark:border-white/10 dark:bg-[#0a0a0a]">
                            <p class="text-sm font-semibold">Fonctionnalités incluses</p>
                            <ul class="mt-2 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                                <li class="flex items-center gap-2"><span class="text-primary-600 dark:text-primary-300 font-semibold">✓</span> Devis & factures</li>
                                <li class="flex items-center gap-2"><span class="text-primary-600 dark:text-primary-300 font-semibold">✓</span> Conversion devis → facture</li>
                                <li class="flex items-center gap-2"><span class="text-primary-600 dark:text-primary-300 font-semibold">✓</span> Génération PDF</li>
                                <li class="flex items-center gap-2"><span class="text-primary-600 dark:text-primary-300 font-semibold">✓</span> Clients & paiements</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Avantages de DigiFact -->
            <section id="benefits" aria-labelledby="benefits-heading" class="mt-16 rounded-3xl border border-black/10 bg-gradient-to-br from-primary-50/50 to-accent-50/30 p-8 dark:border-white/10 dark:from-primary-500/5 dark:to-accent-500/5">
                <h2 id="benefits-heading" class="text-2xl font-semibold tracking-tight">Pourquoi choisir DigiFact ?</h2>
                <p class="mt-2 max-w-2xl text-sm text-slate-600 dark:text-slate-300">La solution de facturation pensée pour les entrepreneurs et PME sénégalaises.</p>

                <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-success-500/20 text-success-700 dark:text-success-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold">100% Gratuit</h3>
                            <p class="mt-1 text-xs text-slate-600 dark:text-slate-400">Aucun coût caché, aucune limite d'utilisation. Démarrez gratuitement.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-accent-500/20 text-accent-700 dark:text-accent-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold">Ultra Rapide</h3>
                            <p class="mt-1 text-xs text-slate-600 dark:text-slate-400">Créez devis et factures en quelques secondes. Efficacité maximale.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-primary-500/20 text-primary-700 dark:text-primary-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold">Sécurisé</h3>
                            <p class="mt-1 text-xs text-slate-600 dark:text-slate-400">Vos données restent confidentielles et protégées. Confiance garantie.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-warning-500/20 text-warning-700 dark:text-warning-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold">Pas d'Engagement</h3>
                            <p class="mt-1 text-xs text-slate-600 dark:text-slate-400">Pas de contrat obligatoire. Utilisez DigiFact aussi longtemps que vous le souhaitez.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- À propos -->
            <section id="about" aria-labelledby="about-heading" class="mt-14">
                <h2 id="about-heading" class="text-2xl font-semibold tracking-tight">À propos</h2>
                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                    DigiFact est conçu pour les indépendants et petites équipes sénégalaises : créez vos devis et factures, suivez les paiements et générez vos PDFs rapidement.
                    Le tout avec une interface moderne, claire et pensée pour un usage quotidien.
                </p>

                <div class="mt-7 grid gap-6 md:grid-cols-3">
                    <article class="rounded-3xl border border-black/10 bg-white/70 p-6 shadow-sm backdrop-blur transition hover:-translate-y-0.5 hover:border-black/20 dark:bg-[#0a0a0a]/60">
                        <h3 class="text-base font-semibold">Vision</h3>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Moins de saisie, plus de visibilité.</p>
                    </article>
                    <article class="rounded-3xl border border-black/10 bg-white/70 p-6 shadow-sm backdrop-blur transition hover:-translate-y-0.5 hover:border-black/20 dark:bg-[#0a0a0a]/60">
                        <h3 class="text-base font-semibold">Objectif</h3>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Avancer vite avec un flux Devis → Facture.</p>
                    </article>
                    <article class="rounded-3xl border border-black/10 bg-white/70 p-6 shadow-sm backdrop-blur transition hover:-translate-y-0.5 hover:border-black/20 dark:bg-[#0a0a0a]/60">
                        <h3 class="text-base font-semibold">Expérience</h3>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Lecture confortable (clair/sombre) & PDF prêt.</p>
                    </article>
                </div>
            </section>

            <!-- Features (Fonctionnalités) -->
            <section aria-labelledby="features" class="mt-14">


                <h2 id="features" class="sr-only">Fonctionnalités</h2>

                <div class="grid gap-6 md:grid-cols-3">
                    <article class="rounded-3xl border border-black/10 bg-white/70 p-6 shadow-sm backdrop-blur transition hover:-translate-y-0.5 hover:border-black/20 dark:border-white/10 dark:bg-[#0a0a0a]/60">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/15 dark:text-indigo-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75v16.5" />
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-base font-semibold">Simple & rapide</h3>
                                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Créez vos documents en quelques clics.</p>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-3xl border border-black/10 bg-white/70 p-6 shadow-sm backdrop-blur transition hover:-translate-y-0.5 hover:border-black/20 dark:border-white/10 dark:bg-[#0a0a0a]/60">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-accent-500/10 text-accent-600 dark:bg-accent-500/15 dark:text-accent-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 12.75 10.5 15.75 16.5 9.75" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-3.2-6.9" />
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-base font-semibold">Clair & sombre</h3>
                                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Contraste optimisé, lecture agréable.</p>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-3xl border border-black/10 bg-white/70 p-6 shadow-sm backdrop-blur transition hover:-translate-y-0.5 hover:border-black/20 dark:border-white/10 dark:bg-[#0a0a0a]/60">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-success-500/10 text-success-700 dark:bg-success-500/15 dark:text-success-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-base font-semibold">PDF & suivi</h3>
                                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Gagnez en visibilité sur vos paiements.</p>
                            </div>
                        </div>
                    </article>
                </div>
            </section>

            <!-- Contact -->
            <section id="contact" class="mt-14">
                <div class="rounded-[2rem] border border-black/10 bg-white/70 p-7 shadow-sm backdrop-blur dark:border-white/10 dark:bg-[#0a0a0a]/60">
                    <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                        <div class="max-w-2xl">
                            <h2 class="text-2xl font-semibold tracking-tight">Contact</h2>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                                Une question sur les devis, factures ou la génération PDF ? Écrivez-nous et on vous répond.
                            </p>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                            <a
                                href="mailto:support@exemple.com"
                                class="inline-flex items-center justify-center rounded-xl bg-primary-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-600/40"
                            >
                                Écrire un email
                            </a>
                            <a
                                href="#main"
                                class="inline-flex items-center justify-center rounded-xl border border-black/10 bg-white px-5 py-3 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-black/5 focus:outline-none focus:ring-2 focus:ring-primary-500/30 dark:border-white/10 dark:bg-[#0a0a0a] dark:text-slate-100 dark:hover:bg-white/5"
                            >
                                Revenir en haut
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Social proof + CTA -->
            <section class="mt-10">

                <div class="rounded-[2rem] border border-black/10 bg-white/70 p-7 shadow-sm backdrop-blur dark:border-white/10 dark:bg-[#0a0a0a]/60">
                    <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                        <div class="max-w-2xl">
                            <h2 class="text-2xl font-semibold tracking-tight">Passez de “ça traîne” à “c’est réglé”.</h2>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                                Devis, factures, relances et exports PDF : gardez tout au même endroit et avancez plus vite.
                            </p>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                            @if (Route::has('login'))
                                @auth
                                    <a
                                        href="{{ url('/dashboard') }}"
                                        class="inline-flex items-center justify-center rounded-xl bg-primary-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-600/40"
                                    >
                                        Ouvrir le dashboard
                                    </a>
                                @else
                                    <a
                                        href="{{ route('register') }}"
                                        class="inline-flex items-center justify-center rounded-xl bg-primary-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-600/40"
                                    >
                                        Commencer gratuitement
                                    </a>
                                    <a
                                        href="{{ route('login') }}"
                                        class="inline-flex items-center justify-center rounded-xl border border-black/10 bg-white px-5 py-3 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-black/5 focus:outline-none focus:ring-2 focus:ring-primary-500/30 dark:border-white/10 dark:bg-[#0a0a0a] dark:text-slate-100 dark:hover:bg-white/5"
                                    >
                                        Voir mon espace
                                    </a>
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            <!-- Personnalisation -->
            <section class="mt-20 mb-20">
                <div class="mx-auto max-w-2xl text-center">
                    <div class="rounded-3xl border border-black/10 bg-gradient-to-br from-primary-50/50 to-accent-50/30 p-10 dark:border-white/10 dark:from-primary-500/5 dark:to-accent-500/5">
                        <div class="flex items-center justify-center h-14 w-14 rounded-2xl bg-primary-500/20 text-primary-700 dark:text-primary-300 mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-8 w-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="mt-6 text-2xl font-semibold tracking-tight">Personnalisez vos documents</h2>
                        <p class="mt-3 text-base leading-relaxed text-slate-600 dark:text-slate-300">
                            Vos factures et devis sont automatiquement personnalisés avec les informations de votre entreprise. Chaque document reflète votre identité professionnelle.
                        </p>
                        <div class="mt-6 flex flex-col gap-2">
                            <p class="text-sm font-medium text-primary-600 dark:text-primary-300">Inclus dans le forfait :</p>
                            <ul class="space-y-2">
                                <li class="flex items-center justify-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <span class="text-primary-600 dark:text-primary-300 font-semibold">✓</span>
                                    Logo et branding personnalisés
                                </li>
                                <li class="flex items-center justify-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <span class="text-primary-600 dark:text-primary-300 font-semibold">✓</span>
                                    Coordonnées entreprise automatiques
                                </li>
                                <li class="flex items-center justify-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <span class="text-primary-600 dark:text-primary-300 font-semibold">✓</span>
                                    Numérotation de factures personnalisée
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <p>© {{ date('Y') }} {{ config('app.name', 'Facturation') }}. Tous droits réservés.</p>
                    <div class="flex gap-4">
                        <a class="hover:text-primary-600 dark:hover:text-primary-300 transition" href="{{ route('terms') }}">Conditions</a>
                        <a class="hover:text-primary-600 dark:hover:text-primary-300 transition" href="{{ route('policy') }}">Confidentialité</a>
                    </div>
                </div>
            </footer>
        </div>
    </header>

    <!-- Minimal dark mode toggle via prefers-color-scheme (no extra backend) -->
    <script>
        (function () {
            const root = document.documentElement;
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (prefersDark) root.classList.add('dark');
        })();
    </script>
</body>
</html>


