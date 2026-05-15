<div class="animate-fade-in" wire:poll.30s="loadStats">
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

            <!-- Period Filter -->
            <div class="flex items-center gap-4 mb-6">
                <span class="text-sm text-gray-500 dark:text-gray-400">Periode:</span>
                <div class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                    <button wire:click="setPeriod('week')"
                        class="px-4 py-1.5 rounded-md text-sm font-medium transition-all {{ $period === 'week' ? 'bg-white dark:bg-gray-600 text-primary-600 dark:text-primary-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                        Semaine
                    </button>
                    <button wire:click="setPeriod('month')"
                        class="px-4 py-1.5 rounded-md text-sm font-medium transition-all {{ $period === 'month' ? 'bg-white dark:bg-gray-600 text-primary-600 dark:text-primary-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                        Mois
                    </button>
                    <button wire:click="setPeriod('year')"
                        class="px-4 py-1.5 rounded-md text-sm font-medium transition-all {{ $period === 'year' ? 'bg-white dark:bg-gray-600 text-primary-600 dark:text-primary-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                        Annee
                    </button>
                </div>
                <button wire:click="loadStats" wire:loading.attr="disabled"
                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors ml-auto"
                    title="Rafraichir">
                    <svg wire:loading.remove wire:target="loadStats" class="w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <svg wire:loading wire:target="loadStats" class="w-5 h-5 animate-spin text-primary-600" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
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
                <!-- Clients Count -->
                <div class="glass-card rounded-2xl p-5 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between">
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
                    </div>
                </div>

                <!-- Invoices Count -->
                <div class="glass-card rounded-2xl p-5 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between">
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
                    </div>
                </div>

                <!-- Quotes -->
                <div class="glass-card rounded-2xl p-5 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between">
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
                        <div class="text-right">
                            <p class="text-lg font-semibold text-emerald-600 dark:text-emerald-400">
                                {{ $stats['accepted_quotes'] ?? 0 }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Acceptes</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Table -->
            <div class="lg:col-span-2">
                <div class="glass-card rounded-2xl p-6 h-full">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Evolution du chiffre d'affaires
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Revenus mensuels des 6 derniers mois</p>
                        </div>
                    </div>

                    @if(isset($stats['chart']) && count($stats['chart']) > 0)
                        <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-700">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Mois</th>
                                        <th
                                            class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Montant</th>
                                        <th
                                            class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Evolution</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                    @foreach($stats['chart'] as $item)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $item['month'] }}
                                            </td>
                                            <td
                                                class="px-4 py-3 text-right text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                                                {{ number_format($item['total'], 0, ',', ' ') }} CFA
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                @if($item['evolution'] !== null)
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                                                            {{ $item['evolution'] >= 0 ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300' : 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300' }}">
                                                        {{ $item['evolution'] >= 0 ? '+' : '' }}{{ $item['evolution'] }}%
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-400 dark:text-gray-500">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary -->
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Total (6 mois)</span>
                                <span class="font-bold text-gray-900 dark:text-white">
{{ number_format($stats['chart']->sum('total'), 0, ',', ' ') }} CFA
                                </span>
                            </div>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="flex flex-col items-center justify-center h-48 text-gray-500 dark:text-gray-400">
                            <svg class="w-16 h-16 mb-4 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <p class="text-center">Aucune donnee de revenus disponible</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Les revenus apparaitront ici une fois
                                les factures payees
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Documents Section -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Documents recents</h3>
                <a href="{{ route('documents.index') }}"
                    class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 flex items-center gap-1 transition-colors">
                    Voir tout
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            @if($recentDocuments->count() > 0)
                <div class="glass-card rounded-2xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50/50 dark:bg-gray-700/50">
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Document</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Client</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Date</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Montant</th>
                                    <th class="px-6 py-4"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($recentDocuments as $doc)
                                    <tr
                                        class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors duration-150 group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 rounded-lg flex items-center justify-center 
                                                                            {{ $doc->type === 'quote' ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400' : 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400' }}">
                                                    @if($doc->type === 'quote')
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linecap="round" stroke-width="2"
                                                                d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $doc->number }}
                                                    </p>
                                                    <p class="text-xs text-gray-400 dark:text-gray-500">
                                                        {{ $doc->type === 'quote' ? 'Devis' : 'Facture' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-900 dark:text-white">{{ $doc->client->name ?? 'N/A' }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4">
                                            {!! $doc->status_badge !!}
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $doc->issue_date->format('d/m/Y') }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ $doc->formatted_total }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('documents.show', $doc) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 dark:text-gray-500 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-gray-700 transition-all">
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
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Commencez par creer votre premier document</p>
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