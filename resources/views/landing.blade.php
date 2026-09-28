<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'DigiFact') }}</title>

    <meta name="description" content="DigiFact : la solution de facturation moderne pour gérer vos devis, factures, paiements et PDF en quelques clics." />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html { color-scheme: light dark; }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        }
        .font-display { font-family: 'Sora', ui-sans-serif, system-ui, sans-serif; }

        /* Fallback : ne masque le contenu que si JS est activé */
        .js [data-reveal] {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .7s cubic-bezier(.22,.61,.36,1), transform .7s cubic-bezier(.22,.61,.36,1);
            will-change: opacity, transform;
        }
        .js [data-reveal="left"] { transform: translateX(-28px); }
        .js [data-reveal="right"] { transform: translateX(28px); }
        .js [data-reveal="zoom"] { transform: scale(.94); }
        .js [data-reveal].is-visible { opacity: 1; transform: translate(0,0) scale(1); }

        @media (prefers-reduced-motion: reduce) {
            .js [data-reveal] { opacity: 1; transform: none; transition: none; }
        }
    </style>
</head>
<body class="min-h-screen antialiased bg-white text-slate-900 dark:bg-[#0a0a0a] dark:text-slate-100">

    <script>document.documentElement.classList.add('js');</script>

    <!-- Fond ambiant -->
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-40 left-1/2 h-[36rem] w-[60rem] -translate-x-1/2 rounded-full bg-primary-500/10 blur-3xl dark:bg-primary-500/10"></div>
        <div class="absolute right-[-10rem] top-40 h-[26rem] w-[26rem] rounded-full bg-primary-500/10 blur-3xl"></div>
        <div class="absolute bottom-[-8rem] left-[-8rem] h-[26rem] w-[26rem] rounded-full bg-success-500/10 blur-3xl"></div>
    </div>

    <a href="#main"
        class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[60] focus:rounded-lg focus:bg-white focus:px-3 focus:py-2 focus:text-sm focus:shadow dark:focus:bg-[#0a0a0a]">
        Aller au contenu
    </a>

    <!-- ===== NAVIGATION ===== -->
    <header class="sticky top-0 z-40 border-b border-slate-200/60 bg-white/80 backdrop-blur-md dark:border-white/10 dark:bg-[#0a0a0a]/80">
        <nav class="mx-auto flex max-w-6xl items-center justify-between px-5 py-4">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 text-white shadow-lg shadow-primary-500/25">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                        <path d="M7 3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2H7zm2 4h6v1H9V7zm0 3h6v1H9v-1zm0 3h4v1H9v-1z"/>
                        <path d="M19 8l.5-.5M20 9l.7-.7M19 12l.5.5M20 15l.7.7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none" opacity="0.7"/>
                    </svg>
                </span>
                <span class="font-display text-[17px] font-semibold tracking-tight text-slate-900 dark:text-white">{{ config('app.name', 'DigiFact') }}</span>
            </a>

            <div class="hidden items-center gap-8 lg:flex">
                <a href="#features" class="text-sm font-medium text-slate-600 transition hover:text-primary-600 dark:text-slate-300 dark:hover:text-primary-300">Fonctionnalités</a>
                <a href="#about" class="text-sm font-medium text-slate-600 transition hover:text-primary-600 dark:text-slate-300 dark:hover:text-primary-300">À propos</a>
            </div>

            <div class="flex items-center gap-2">
                <button id="theme-toggle" type="button" aria-label="Basculer le thème"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 dark:border-white/10 dark:bg-[#0a0a0a] dark:text-slate-300 dark:hover:bg-white/5">
                    <svg id="theme-toggle-dark-icon" class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg id="theme-toggle-light-icon" class="hidden h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/></svg>
                </button>

                <button type="button"
                    class="lg:hidden rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-800 shadow-sm transition hover:bg-slate-50 dark:border-white/10 dark:bg-[#0a0a0a] dark:text-white dark:hover:bg-white/5"
                    onclick="document.getElementById('mobile-nav').classList.toggle('hidden')">
                    Menu
                </button>

                <div id="mobile-nav"
                    class="hidden absolute right-4 top-16 z-50 w-56 flex-col gap-1 rounded-2xl border border-slate-200 bg-white p-2 shadow-xl dark:border-white/10 dark:bg-[#111111] dark:shadow-black/50 lg:hidden">
                    <a href="#features" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-50 dark:hover:bg-white/5">Fonctionnalités</a>
                    <a href="#about" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-50 dark:hover:bg-white/5">À propos</a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="mt-1 rounded-lg bg-gradient-to-r from-primary-500 to-primary-600 px-3 py-2 text-center text-sm font-semibold text-white">Tableau de bord</a>
                    @else
                        <a href="{{ route('login') }}" class="mt-1 rounded-lg bg-gradient-to-r from-primary-500 to-primary-600 px-3 py-2 text-center text-sm font-semibold text-white">Connexion</a>
                    @endauth
                </div>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="hidden rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-primary-500/25 transition hover:from-primary-600 hover:to-primary-700 lg:inline-flex">
                            Tableau de bord
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="hidden rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-white/10 dark:bg-[#0a0a0a] dark:text-slate-200 dark:hover:bg-white/5 lg:inline-flex">
                            Connexion
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="hidden rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-primary-500/25 transition hover:from-primary-600 hover:to-primary-700 lg:inline-flex">
                                Créer un compte
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>
    </header>

    <main id="main">

        <!-- ===== HERO ===== -->
        <section class="mx-auto max-w-6xl px-5 pt-16 pb-16">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div data-reveal>
                    <div class="inline-flex items-center gap-2 rounded-full border border-primary-200 bg-primary-50 px-3 py-1 text-xs font-medium text-primary-700 dark:border-primary-500/30 dark:bg-primary-500/10 dark:text-primary-300">
                        <span class="inline-flex h-2 w-2 rounded-full bg-success-500"></span>
                        Gratuit — devis, factures, paiements et PDF
                    </div>

                    <h1 class="font-display mt-6 text-4xl font-bold leading-tight tracking-tight text-slate-900 dark:text-white md:text-5xl">
                        La facturation qui <span class="bg-gradient-to-r from-primary-500 to-primary-700 bg-clip-text text-transparent">suit votre rythme</span>
                    </h1>

                    <p class="mt-5 max-w-md text-base leading-relaxed text-slate-600 dark:text-slate-300">
                        Créez vos devis, transformez-les en factures et suivez chaque paiement au même endroit. Pensé pour les entrepreneurs et PME sénégalaises.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-primary-500/30 transition hover:from-primary-600 hover:to-primary-700">
                                    Ouvrir mon tableau de bord
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            @else
                                <a href="{{ route('register') }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-primary-500/30 transition hover:from-primary-600 hover:to-primary-700">
                                    Commencer gratuitement
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                                <a href="{{ route('login') }}"
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-7 py-3 text-sm font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50 dark:border-white/10 dark:bg-[#0a0a0a] dark:text-slate-100 dark:hover:bg-white/5">
                                    Se connecter
                                </a>
                            @endauth
                        @endif
                    </div>

                    <dl class="mt-12 grid max-w-md grid-cols-3 gap-6 border-t border-slate-200 pt-6 dark:border-white/10">
                        <div>
                            <dt class="font-display text-2xl font-bold text-slate-900 dark:text-white">80%</dt>
                            <dd class="mt-1 text-xs text-slate-500 dark:text-slate-400">de temps gagné sur la saisie</dd>
                        </div>
                        <div>
                            <dt class="font-display text-2xl font-bold text-slate-900 dark:text-white">3 étapes</dt>
                            <dd class="mt-1 text-xs text-slate-500 dark:text-slate-400">devis, facture, paiement</dd>
                        </div>
                        <div>
                            <dt class="font-display text-2xl font-bold text-slate-900 dark:text-white">0 FCFA</dt>
                            <dd class="mt-1 text-xs text-slate-500 dark:text-slate-400">pour démarrer</dd>
                        </div>
                    </dl>
                </div>

                <div data-reveal="zoom" class="relative">
                    <div class="absolute inset-0 -z-10 rounded-[2rem] bg-gradient-to-br from-primary-500/20 via-primary-500/10 to-transparent blur-2xl"></div>
                    <img src="{{ asset('img/landing/hero.svg') }}" alt="Illustration de la facturation DigiFact"
                        class="mx-auto w-full max-w-lg drop-shadow-2xl" />
                </div>
            </div>
        </section>

        <!-- ===== FONCTIONNALITÉS ===== -->
        <section id="features" class="mx-auto max-w-6xl px-5 py-20">
            <div class="mx-auto max-w-2xl text-center" data-reveal>
                <span class="inline-flex items-center gap-2 rounded-full bg-primary-500/10 px-3 py-1 text-xs font-semibold text-primary-700 dark:text-primary-300">Fonctionnalités</span>
                <h2 class="font-display mt-4 text-3xl font-bold tracking-tight text-slate-900 dark:text-white md:text-4xl">
                    Tout le nécessaire pour <span class="bg-gradient-to-r from-primary-500 to-primary-700 bg-clip-text text-transparent">facturer sereinement</span>
                </h2>
                <p class="mt-3 text-base text-slate-600 dark:text-slate-300">
                    Du devis à la facture, chaque étape est simple, rapide et professionnelle.
                </p>
            </div>

            <div class="mt-14 grid gap-6 md:grid-cols-3">
                <article data-reveal class="group rounded-3xl border border-slate-200 bg-white/80 p-7 shadow-sm backdrop-blur transition hover:-translate-y-1 hover:shadow-xl dark:border-white/10 dark:bg-[#0d0d0d]/80">
                    <div class="flex h-40 items-center justify-center overflow-hidden rounded-2xl bg-slate-50 dark:bg-[#161616]">
                        <img src="{{ asset('img/landing/feature-quote.svg') }}" alt="Créer des devis" class="h-full w-auto object-contain transition duration-300 group-hover:scale-110" />
                    </div>
                    <h3 class="font-display mt-5 text-lg font-semibold text-slate-900 dark:text-white">Devis en un clic</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-300">Composez un devis clair, puis transformez-le en facture dès qu'il est accepté.</p>
                </article>

                <article data-reveal data-delay="120" class="group rounded-3xl border border-slate-200 bg-white/80 p-7 shadow-sm backdrop-blur transition hover:-translate-y-1 hover:shadow-xl dark:border-white/10 dark:bg-[#0d0d0d]/80">
                    <div class="flex h-40 items-center justify-center overflow-hidden rounded-2xl bg-slate-50 dark:bg-[#161616]">
                        <img src="{{ asset('img/landing/feature-invoice.svg') }}" alt="Générer des factures PDF" class="h-full w-auto object-contain transition duration-300 group-hover:scale-110" />
                    </div>
                    <h3 class="font-display mt-5 text-lg font-semibold text-slate-900 dark:text-white">Factures & PDF</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-300">Un PDF propre et personnalisé, prêt à envoyer à vos clients à chaque fois.</p>
                </article>

                <article data-reveal data-delay="240" class="group rounded-3xl border border-slate-200 bg-white/80 p-7 shadow-sm backdrop-blur transition hover:-translate-y-1 hover:shadow-xl dark:border-white/10 dark:bg-[#0d0d0d]/80">
                    <div class="flex h-40 items-center justify-center overflow-hidden rounded-2xl bg-slate-50 dark:bg-[#161616]">
                        <img src="{{ asset('img/landing/feature-payment.svg') }}" alt="Suivre les paiements" class="h-full w-auto object-contain transition duration-300 group-hover:scale-110" />
                    </div>
                    <h3 class="font-display mt-5 text-lg font-semibold text-slate-900 dark:text-white">Paiements suivis</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-300">Repérez d'un coup d'œil ce qui est payé, en attente ou en retard.</p>
                </article>
            </div>
        </section>

        <!-- ===== PARCOURS ===== -->
        <section class="border-y border-slate-200 bg-gradient-to-br from-primary-50/60 to-primary-100/40 py-20 dark:border-white/10 dark:from-primary-500/5 dark:to-primary-500/10">
            <div class="mx-auto max-w-6xl px-5">
                <div class="mx-auto max-w-2xl text-center" data-reveal>
                    <h2 class="font-display text-3xl font-bold tracking-tight text-slate-900 dark:text-white md:text-4xl">
                        Du devis au paiement, sans rupture
                    </h2>
                    <p class="mt-3 text-base text-slate-600 dark:text-slate-300">Trois étapes, un seul outil, aucune ressaisie.</p>
                </div>

                <div class="mt-14 grid gap-8 md:grid-cols-3">
                    <div data-reveal class="relative rounded-3xl border border-slate-200 bg-white/80 p-7 shadow-sm backdrop-blur dark:border-white/10 dark:bg-[#0d0d0d]/80">
                        <span class="font-display flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-primary-500 to-primary-600 text-sm font-bold text-white shadow-lg shadow-primary-500/25">1</span>
                        <h3 class="font-display mt-5 text-lg font-semibold text-slate-900 dark:text-white">Créer un devis</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-300">Ajoutez vos prestations et vos prix, votre devis est prêt en quelques minutes.</p>
                    </div>

                    <div data-reveal data-delay="120" class="relative rounded-3xl border border-slate-200 bg-white/80 p-7 shadow-sm backdrop-blur dark:border-white/10 dark:bg-[#0d0d0d]/80">
                        <span class="font-display flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-primary-600 to-primary-700 text-sm font-bold text-white shadow-lg shadow-primary-500/25">2</span>
                        <h3 class="font-display mt-5 text-lg font-semibold text-slate-900 dark:text-white">Convertir en facture</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-300">Une fois le devis accepté, transformez-le en facture officielle en un clic.</p>
                    </div>

                    <div data-reveal data-delay="240" class="relative rounded-3xl border border-slate-200 bg-white/80 p-7 shadow-sm backdrop-blur dark:border-white/10 dark:bg-[#0d0d0d]/80">
                        <span class="font-display flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-success-500 to-success-600 text-sm font-bold text-white shadow-lg shadow-success-500/25">3</span>
                        <h3 class="font-display mt-5 text-lg font-semibold text-slate-900 dark:text-white">Suivre le paiement</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-300">Repérez en un coup d'œil ce qui est payé, en attente ou en retard.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== À PROPOS ===== -->
        <section id="about" class="mx-auto max-w-6xl px-5 py-20">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div data-reveal="left" class="order-2 lg:order-1">
                    <div class="relative mx-auto max-w-md">
                        <div class="absolute inset-0 -z-10 rotate-2 rounded-3xl bg-primary-500/10 blur-2xl"></div>
                        <img src="{{ asset('img/landing/about.svg') }}" alt="Entrepreneur utilisant DigiFact"
                            class="w-full drop-shadow-xl" loading="lazy" />
                    </div>
                </div>
                <div data-reveal="right" class="order-1 lg:order-2">
                    <span class="inline-flex items-center gap-2 rounded-full bg-primary-500/10 px-3 py-1 text-xs font-semibold text-primary-700 dark:text-primary-300">À propos</span>
                    <h2 class="font-display mt-4 text-3xl font-bold tracking-tight text-slate-900 dark:text-white md:text-4xl">
                        Pensé pour les <span class="bg-gradient-to-r from-primary-500 to-primary-700 bg-clip-text text-transparent">PME et indépendants</span>
                    </h2>
                    <p class="mt-4 text-base leading-relaxed text-slate-600 dark:text-slate-300">
                        DigiFact est né d'un constat simple : la facturation prend trop de temps quand on gère seul son activité. L'outil couvre le devis, la facture, le PDF et le suivi des paiements — sans complexité inutile.
                    </p>

                    <div class="mt-8 space-y-5">
                        <div class="flex gap-4">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-success-500/15 text-success-700 dark:text-success-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-display text-base font-semibold text-slate-900 dark:text-white">100% gratuit</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-300">Aucun coût caché, aucune limite d'utilisation.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-primary-500/15 text-primary-700 dark:text-primary-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18zm0-8a1 1 0 100-2 1 1 0 000 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-display text-base font-semibold text-slate-900 dark:text-white">Mentions sénégalaises incluses</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-300">NINEA, RC et FCFA déjà pré-remplis dans vos documents.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-primary-700/15 text-primary-700 dark:text-primary-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-display text-base font-semibold text-slate-900 dark:text-white">Relances en un clic</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-300">Contactez vos clients avant qu'une facture ne prenne du retard.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== CTA FINAL ===== -->
        <section class="mx-auto max-w-6xl px-5 pb-20">
            <div data-reveal="zoom" class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 px-8 py-16 text-center shadow-2xl dark:border-white/10">
                <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div>
                <div class="absolute -bottom-20 -left-16 h-64 w-64 rounded-full bg-primary-400/20 blur-2xl"></div>

                <div class="relative mx-auto max-w-2xl">
                    <h2 class="font-display text-3xl font-bold tracking-tight text-white md:text-4xl">
                        Passez de « ça traîne » à « c'est réglé »
                    </h2>
                    <p class="mx-auto mt-4 max-w-md text-sm text-blue-100">
                        Devis, factures et relances au même endroit. Aucune carte bancaire requise.
                    </p>
                    <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-7 py-3 text-sm font-semibold text-primary-700 shadow-lg transition hover:bg-slate-50">
                                    Ouvrir le tableau de bord
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-7 py-3 text-sm font-semibold text-primary-700 shadow-lg transition hover:bg-slate-50">
                                    Commencer gratuitement
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl border border-white/30 bg-white/10 px-7 py-3 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20">
                                    Se connecter
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== FOOTER ===== -->
        <footer class="border-t border-slate-200 py-8 dark:border-white/10">
            <div class="mx-auto flex max-w-6xl flex-col gap-3 px-5 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    © {{ date('Y') }} {{ config('app.name', 'DigiFact') }}. Tous droits réservés.
                </p>
                <div class="flex gap-5 text-sm">
                    <a href="{{ route('terms.show') }}" class="text-slate-600 transition hover:text-primary-600 dark:text-slate-400 dark:hover:text-primary-300">Conditions</a>
                    <a href="{{ route('policy.show') }}" class="text-slate-600 transition hover:text-primary-600 dark:text-slate-400 dark:hover:text-primary-300">Confidentialité</a>
                </div>
            </div>
        </footer>
    </main>

    <!-- Bascule de thème clair/sombre + animations au scroll -->
    <script>
        (function () {
            const root = document.documentElement;
            const btn = document.getElementById('theme-toggle');
            const darkIcon = document.getElementById('theme-toggle-dark-icon');
            const lightIcon = document.getElementById('theme-toggle-light-icon');
            if (!btn) return;

            // Applique le thème enregistré (par défaut : clair)
            const saved = localStorage.getItem('color-theme');
            if (saved === 'dark') root.classList.add('dark');

            function syncIcons() {
                if (root.classList.contains('dark')) {
                    lightIcon.classList.remove('hidden');
                    darkIcon.classList.add('hidden');
                } else {
                    darkIcon.classList.remove('hidden');
                    lightIcon.classList.add('hidden');
                }
            }
            syncIcons();

            btn.addEventListener('click', function () {
                if (root.classList.contains('dark')) {
                    root.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    root.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
                syncIcons();
            });
        })();

        // Animations au scroll
        (function () {
            const items = document.querySelectorAll('[data-reveal]');
            if (!items.length) return;
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        const delay = el.getAttribute('data-delay') || 0;
                        el.style.transitionDelay = delay + 'ms';
                        el.classList.add('is-visible');
                        observer.unobserve(el);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });
            items.forEach((el) => observer.observe(el));
        })();
    </script>
</body>
</html>
