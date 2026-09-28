<div class="animate-fade-in" wire:poll.visible.60s="loadStats">
    <!-- Header Section -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Welcome & Quick Actions Row -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
                <!-- Welcome Text -->
                <div>
                    <h1 class="page-title">Tableau de bord</h1>
                    <p class="mt-1 text-gray-500 dark:text-gray-400">Bienvenue sur votre espace de facturation</p>
                </div>
            </div>

            <!-- Période -->
                <div class="mb-6">
                    <div class="flex items-center gap-4 flex-wrap">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Période :</span>
                        <div class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1" x-data="{ active: @entangle('period') }">
                            <button wire:click="setPeriod('week')" @click="active = 'week'"
                                class="px-4 py-1.5 rounded-md text-sm font-medium transition-all"
                                :class="active === 'week' ? 'bg-white dark:bg-gray-600 text-primary-600 dark:text-primary-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'">
                                Semaine
                            </button>
                            <button wire:click="setPeriod('month')" @click="active = 'month'"
                                class="px-4 py-1.5 rounded-md text-sm font-medium transition-all"
                                :class="active === 'month' ? 'bg-white dark:bg-gray-600 text-primary-600 dark:text-primary-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'">
                                Mois
                            </button>
                            <button wire:click="setPeriod('threemonths')" @click="active = 'threemonths'"
                                class="px-4 py-1.5 rounded-md text-sm font-medium transition-all"
                                :class="active === 'threemonths' ? 'bg-white dark:bg-gray-600 text-primary-600 dark:text-primary-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'">
                                3 mois
                            </button>
                            <button wire:click="setPeriod('sixmonths')" @click="active = 'sixmonths'"
                                class="px-4 py-1.5 rounded-md text-sm font-medium transition-all"
                                :class="active === 'sixmonths' ? 'bg-white dark:bg-gray-600 text-primary-600 dark:text-primary-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'">
                                6 mois
                            </button>
                            <button wire:click="setPeriod('year')" @click="active = 'year'"
                                class="px-4 py-1.5 rounded-md text-sm font-medium transition-all"
                                :class="active === 'year' ? 'bg-white dark:bg-gray-600 text-primary-600 dark:text-primary-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'">
                                Année
                            </button>
                        </div>
                        <button wire:click="loadStats" wire:loading.attr="disabled"
                            class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors ml-auto"
                            title="Rafraîchir">
                            <svg wire:loading.remove wire:target="loadStats" class="w-5 h-5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <svg wire:loading wire:target="loadStats" class="w-5 h-5 animate-spin text-primary-600"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </button>
                    </div>

                    @if($activeRangeLabel)
                        <p class="mt-3 flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                            <span>Période calculée : {{ $activeRangeLabel }}</span>
                            <span wire:loading.delay.shortest
                                class="inline-flex items-center gap-1 text-primary-600 dark:text-primary-400 font-medium">
                                <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Mise à jour…
                            </span>
                        </p>
                    @endif
                </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <!-- Main Stats Grid - Financial Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Revenue Card -->
            <div
                class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span
                            class="text-xs font-medium text-emerald-100 bg-white/10 px-2 py-1 rounded-full">Revenus</span>
                    </div>
                    <p class="text-3xl font-bold text-white">
                        {{ number_format($stats['revenue'] ?? 0, 0, ',', ' ') }}
                        <span class="text-sm font-normal text-white/80">CFA</span>
                    </p>
                    <p class="text-sm text-white/80 mt-1">Chiffre d'affaires</p>
                </div>
            </div>
            <!-- Pending Payment Card -->
            <div
                class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-amber-100 bg-white/10 px-2 py-1 rounded-full">A
                            payer</span>
                    </div>
                    <p class="text-3xl font-bold text-white">{{ $stats['pending'] ?? 0 }}</p>
                    <p class="text-sm text-white/80 mt-1">En attente</p>
                </div>
            </div>

            <!-- Overdue Card -->
            <div 
            class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-red-500 to-red-600 p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-rose-100 bg-white/10 px-2 py-1 rounded-full">Urgent</span>
                    </div>
                    <p class="text-3xl font-bold text-white">{{ $stats['overdue'] ?? 0 }}</p>
                    <p class="text-sm text-white/80 mt-1">En retard</p>
                </div>
            </div>

            <!-- Payment Rate Card -->
            <div
                class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <span
                            class="text-xs font-medium text-violet-100 bg-white/10 px-2 py-1 rounded-full">Performance</span>
                    </div>
                    <p class="text-3xl font-bold text-white">{{ $stats['payment_rate'] ?? 0 }}%</p>
                    <p class="text-sm text-white/80 mt-1">Taux de paiement</p>
                </div>
            </div>
        </div>

        <!-- Secondary Stats & Chart Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Mini Stats Cards -->
            <div class="lg:col-span-1 space-y-4">
                @php
                    // Répartition clients (actifs / inactifs) — segment gris si aucune donnée
                    $clientsD = $stats['clients_donut'] ?? ['actifs' => 0, 'inactifs' => 0];
                    $clientsDonutTotal = ($clientsD['actifs'] ?? 0) + ($clientsD['inactifs'] ?? 0);
                    $clientsDonut = $clientsDonutTotal > 0
                        ? ['labels' => ['Actifs', 'Inactifs'], 'values' => [(int) $clientsD['actifs'], (int) $clientsD['inactifs']], 'colors' => ['#10b981', '#ef4444']]
                        : ['labels' => ['Aucune donnée'], 'values' => [1], 'colors' => ['#cbd5e1']];

                    // Répartition factures (payées / en attente / en retard)
                    $invoicesD = $stats['invoices_donut'] ?? ['payees' => 0, 'en_attente' => 0, 'en_retard' => 0];
                    $invoicesDonutTotal = ($invoicesD['payees'] ?? 0) + ($invoicesD['en_attente'] ?? 0) + ($invoicesD['en_retard'] ?? 0);
                    $invoicesDonut = $invoicesDonutTotal > 0
                        ? ['labels' => ['Payées', 'En attente', 'En retard'], 'values' => [(int) $invoicesD['payees'], (int) $invoicesD['en_attente'], (int) $invoicesD['en_retard']], 'colors' => ['#10b981', '#f59e0b', '#ef4444']]
                        : ['labels' => ['Aucune donnée'], 'values' => [1], 'colors' => ['#cbd5e1']];

                    // Répartition devis (en cours / acceptés / refusés)
                    $quotesDonutTotal = ($stats['quotes'] ?? 0) + ($stats['accepted_quotes'] ?? 0) + ($stats['refused_quotes'] ?? 0);
                    $quotesDonut = $quotesDonutTotal > 0
                        ? ['labels' => ['En cours', 'Acceptés', 'Refusés'], 'values' => [(int) ($stats['quotes'] ?? 0), (int) ($stats['accepted_quotes'] ?? 0), (int) ($stats['refused_quotes'] ?? 0)], 'colors' => ['#6366f1', '#10b981', '#ef4444']]
                        : ['labels' => ['Aucune donnée'], 'values' => [1], 'colors' => ['#cbd5e1']];
                @endphp

                <!-- Clients Count -->
                <div class="glass-card rounded-2xl p-5 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-sky-400 to-sky-500 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $stats['clients_count'] ?? 0 }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Clients</p>
                            </div>
                        </div>
                        <div class="relative w-20 h-20 shrink-0" title="Clients actifs / inactifs">
                            <canvas
                                data-chart="donut"
                                data-labels='@json($clientsDonut['labels'])'
                                data-values='@json($clientsDonut['values'])'
                                data-colors='@json($clientsDonut['colors'])'>
                            </canvas>
                        </div>
                    </div>
                </div>

                <!-- Invoices Count -->
                <div class="glass-card rounded-2xl p-5 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-400 to-green-500 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linecap="round" stroke-width="2"
                                        d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $stats['invoices_count'] ?? 0 }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Factures</p>
                            </div>
                        </div>
                        <div class="relative w-20 h-20 shrink-0" title="Factures payées / en attente / en retard">
                            <canvas
                                data-chart="donut"
                                data-labels='@json($invoicesDonut['labels'])'
                                data-values='@json($invoicesDonut['values'])'
                                data-colors='@json($invoicesDonut['colors'])'>
                            </canvas>
                        </div>
                    </div>
                </div>

                <!-- Quotes -->
                <div class="glass-card rounded-2xl p-5 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-400 to-indigo-500 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['quotes'] ?? 0 }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Devis en cours</p>
                            </div>
                        </div>
                        <div class="relative w-20 h-20 shrink-0" title="Devis en cours / acceptés / refusés">
                            <canvas
                                data-chart="donut"
                                data-labels='@json($quotesDonut['labels'])'
                                data-values='@json($quotesDonut['values'])'
                                data-colors='@json($quotesDonut['colors'])'>
                            </canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphique : Évolution du chiffre d'affaires -->
            <div class="lg:col-span-2">
                <div class="glass-card rounded-2xl p-6 h-full flex flex-col">
                    <div class="flex items-start justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Évolution du chiffre d'affaires
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $stats['chart_subtitle'] ?? 'Revenus sur la période' }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-xs uppercase tracking-wider text-gray-400 dark:text-gray-500">Total période</p>
                            <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400">
                                {{ number_format($stats['chart']->sum('value'), 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                    </div>

                    <div class="relative flex-1 w-full h-56 sm:h-64">
                        <canvas
                            data-chart="revenue"
                            data-labels='@json($stats['chart']->pluck('label'))'
                            data-values='@json($stats['chart']->pluck('value')->map(fn ($v) => (int) $v))'>
                        </canvas>

                        @if($stats['chart']->sum('value') <= 0)
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <svg class="w-10 h-10 mb-2 text-gray-300 dark:text-gray-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Les revenus apparaîtront ici une fois
                                    les factures payées
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            </div>
        </div>

        <!-- Recent Documents Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-5 pt-1">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Documents récents</h3>
                <a href="{{ route('documents.index') }}"
                    class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 flex items-center gap-1 transition-colors">
                    Voir tout
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            @if($recentDocuments->count() > 0)
                <div class="glass-card rounded-2xl overflow-hidden py-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50/50 dark:bg-gray-700/50">
                                    <th
                                        class="px-8 py-5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Document</th>
                                    <th
                                        class="px-8 py-5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Client</th>
                                    <th
                                        class="px-8 py-5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Statut</th>
                                    <th
                                        class="px-8 py-5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Date</th>
                                    <th
                                        class="px-8 py-5 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Montant</th>
                                    <th class="px-8 py-5"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                                @foreach($recentDocuments as $doc)
                                    <tr
                                        class="relative hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150 group cursor-pointer">
                                        <td class="px-8 py-5 whitespace-nowrap">
                                            <a href="{{ route('documents.show', $doc) }}"
                                                class="absolute inset-0" aria-label="Voir {{ $doc->number }}"></a>
                                            <div class="flex items-center gap-3">
                                                @php
                                                    $isQuote = $doc->type === 'quote';
                                                @endphp
                                                <div
                                                    class="w-10 h-10 rounded-lg flex items-center justify-center
                                                            {{ $isQuote ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-400' : 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-400' }}">
                                                    @switch($doc->type)
                                                        @case('quote')
                                                            {{-- Devis : calculatrice (estimation / proposition chiffrée) --}}
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.7"
                                                                viewBox="0 0 24 24" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M7.5 3h9A2.5 2.5 0 0119 5.5v13a2.5 2.5 0 01-2.5 2.5h-9A2.5 2.5 0 015 18.5v-13A2.5 2.5 0 017.5 3z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M8.25 7.75h7.5" />
                                                                <path stroke-linecap="round" stroke-width="2.6"
                                                                    d="M9 12.75h.01M12 12.75h.01M15 12.75h.01M9 16.5h.01M12 16.5h.01M15 16.5h.01" />
                                                            </svg>
                                                            @break

                                                        @default
                                                            {{-- Facture : reçu avec symbole monétaire --}}
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.7"
                                                                viewBox="0 0 24 24" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M6.75 3.75h10.5a1.5 1.5 0 011.5 1.5v15l-2.25-1.5-2.25 1.5-2.25-1.5-2.25 1.5-2.25-1.5-2.25 1.5v-15a1.5 1.5 0 011.5-1.5z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M12 8.25v7.5" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M13.9 10.4c-.4-.42-1.05-.66-1.9-.66-1.05 0-1.85.5-1.85 1.28 0 .72.6 1.05 1.95 1.3 1.4.26 2.15.78 2.15 1.75 0 1.06-.95 1.73-2.35 1.73-.92 0-1.68-.26-2.1-.74" />
                                                            </svg>
                                                    @endswitch
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $doc->number }}
                                                    </p>
                                                    <p class="text-xs text-gray-400 dark:text-gray-500">
                                                        {{ $isQuote ? 'Devis' : 'Facture' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 whitespace-nowrap">
                                            <p class="text-sm text-gray-900 dark:text-white">{{ $doc->client->name ?? 'N/A' }}
                                            </p>
                                        </td>
                                        <td class="px-8 py-5 whitespace-nowrap">
                                            {!! $doc->status_badge !!}
                                        </td>
                                        <td class="px-8 py-5 whitespace-nowrap">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $doc->issue_date->format('d/m/Y') }}</p>
                                        </td>
                                        <td class="px-8 py-5 text-right whitespace-nowrap">
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ $doc->formatted_total }}
                                            </p>
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            <a href="{{ route('documents.show', $doc) }}"
                                                class="relative z-10 inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 dark:text-gray-500 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-gray-700 transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="glass-card rounded-2xl p-12 text-center">
                    <div
                        class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Aucun document</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Commencez par créer votre premier document</p>
                    <div class="flex justify-center gap-3">
                        <a href="{{ route('documents.create', 'invoice') }}" class="btn-primary">
                            Nouveau devis
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>