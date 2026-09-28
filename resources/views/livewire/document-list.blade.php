<div class="animate-fade-in">
    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 transition-opacity" wire:click="cancelDelete">
            </div>

            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Modal Panel -->
                <div
                    class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <!-- Icon -->
                    <div class="bg-red-50 dark:bg-red-900/30 px-4 py-4 sm:px-6 flex items-center justify-center">
                        <div
                            class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/50">
                            <svg class="h-7 w-7 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-xl leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">
                                    Confirmer la suppression
                                </h3>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Êtes-vous sûr de vouloir supprimer le document
                                        <strong class="text-gray-900 dark:text-white">{{ $documentNumberToDelete }}</strong>
                                        ?
                                    </p>
                                    <p class="text-sm text-red-500 dark:text-red-400 mt-3">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        Cette action est irréversible. Tous les éléments et paiements associés seront
                                        également supprimés.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                        <button wire:click="delete" type="button"
                            class="inline-flex w-full justify-center rounded-xl bg-gradient-to-r from-red-500 to-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg hover:from-red-600 hover:to-red-700 sm:ml-3 sm:w-auto transition-all duration-200 transform hover:scale-[1.02]">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Supprimer
                        </button>
                        <button wire:click="cancelDelete" type="button"
                            class="mt-3 inline-flex w-full justify-center rounded-xl bg-white dark:bg-gray-600 px-4 py-2.5 text-sm font-semibold text-gray-900 dark:text-white shadow-lg ring-1 ring-inset ring-gray-300 dark:ring-gray-500 hover:bg-gray-50 dark:hover:bg-gray-500 sm:mt-0 sm:w-auto transition-all duration-200">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Header Section -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="page-title">Documents</h1>
                    <p class="mt-1 text-gray-500 dark:text-gray-400">Gérez vos factures et devis</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('documents.create', 'quote') }}"
                        class="btn-primary bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nouveau devis
                    </a>

                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <!-- Filters -->
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div class="sm:col-span-1">
                    <label for="search"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rechercher</label>
                    <div class="relative">
                        <input type="text" wire:model.live="search" id="search" class="input-modern pl-10"
                            placeholder="Numéro ou client...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        @if($search)
                            <button wire:click="$set('search', '')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linecap="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>

                <div class="sm:col-span-1">
                    <label for="type"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                    <select wire:model.live="type" id="type" class="input-modern">
                        <option value="all">Tous</option>
                        <option value="quote">Devis</option>
                        <option value="invoice">Factures</option>
                    </select>
                </div>

                <div class="sm:col-span-1">
                    <label for="status"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Statut</label>
                    <select wire:model.live="status" id="status" class="input-modern">
                        <option value="all">Tous</option>
                        <option value="draft">Brouillon</option>
                        <option value="sent">Envoyé</option>
                        <option value="viewed">Vu</option>
                        <option value="accepted">Accepté</option>
                        <option value="refused">Refusé</option>
                        <option value="paid">Payé</option>
                        <option value="partial_paid">Partiellement payé</option>
                        <option value="overdue">En retard</option>
                        <option value="cancelled">Annulé</option>
                    </select>
                </div>

                <div class="sm:col-span-1 flex items-end">
                    @if($search || $type !== 'all' || $status !== 'all')
                        <button wire:click="resetFilters" class="btn-secondary w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Réinitialiser
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Documents List -->
        @if($documents->count() > 0)
            <div class="glass-card rounded-2xl overflow-hidden">
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($documents as $document)
                        <div
                            class="flex items-center justify-between p-5 hover:bg-gray-50/80 dark:hover:bg-gray-700/30 transition-all duration-200 group">
                            <a href="{{ route('documents.show', $document) }}" class="flex items-center gap-5 flex-1">
                                <!-- Document Type Icon : les valeurs réelles sont 'quote' et 'invoice' -->
                                @php
                                    $isQuote = $document->type === 'quote';
                                @endphp
                                <div
                                    class="w-14 h-14 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110 shadow-md
                                            {{ $isQuote
                                                ? 'bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/50 dark:to-blue-800/50 text-blue-700 dark:text-blue-400'
                                                : 'bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/50 dark:to-green-800/50 text-green-700 dark:text-green-400' }}">
                                    @switch($document->type)
                                        @case('quote')
                                            {{-- Devis : calculatrice (estimation / proposition chiffrée) --}}
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.7"
                                                viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M7.5 3h9A2.5 2.5 0 0119 5.5v13a2.5 2.5 0 01-2.5 2.5h-9A2.5 2.5 0 015 18.5v-13A2.5 2.5 0 017.5 3z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.75h7.5" />
                                                <path stroke-linecap="round" stroke-width="2.6"
                                                    d="M9 12.75h.01M12 12.75h.01M15 12.75h.01M9 16.5h.01M12 16.5h.01M15 16.5h.01" />
                                            </svg>
                                            @break

                                        @default
                                            {{-- Facture : reçu avec symbole monétaire --}}
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.7"
                                                viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6.75 3.75h10.5a1.5 1.5 0 011.5 1.5v15l-2.25-1.5-2.25 1.5-2.25-1.5-2.25 1.5-2.25-1.5-2.25 1.5v-15a1.5 1.5 0 011.5-1.5z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v7.5" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M13.9 10.4c-.4-.42-1.05-.66-1.9-.66-1.05 0-1.85.5-1.85 1.28 0 .72.6 1.05 1.95 1.3 1.4.26 2.15.78 2.15 1.75 0 1.06-.95 1.73-2.35 1.73-.92 0-1.68-.26-2.1-.74" />
                                            </svg>
                                    @endswitch
                                </div>

                                <!-- Document Info -->
                                <div>
                                    <div class="flex items-center gap-3 mb-1">
                                        <p
                                            class="font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors text-lg">
                                            {{ $document->number }}
                                        </p>
                                        <span
                                            class="px-2.5 py-1 rounded-full text-xs font-semibold 
                                                    {{ $isQuote ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300' }}">
                                            {{ $isQuote ? 'Devis' : 'Facture' }}
                                        </span>
                                        <div>
                                            {!! $document->status_badge !!}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            {{ $document->client->name ?? 'N/A' }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $document->issue_date->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>
                            </a>

                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $document->formatted_total }}
                                    </p>
                                    <p
                                        class="text-sm {{ $document->due_date_status === 'overdue' ? 'text-red-500 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}">
                                        Échéance: {{ $document->due_date->format('d/m/Y') }}
                                    </p>
                                </div>
                                <!-- Delete Button -->
                                <button wire:click="confirmDelete({{ $document->id }})"
                                    class="p-2.5 text-gray-400 dark:text-gray-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all duration-200"
                                    title="Supprimer le document">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                <svg class="w-6 h-6 text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300 group-hover:translate-x-1 transition-all"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6 px-6 pb-6">
                    {{ $documents->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="glass-card rounded-2xl p-12 text-center">
                <div
                    class="w-24 h-24 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Aucun document trouvé</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">
                    @if($search || $type !== 'all' || $status !== 'all')
                        Aucun document ne correspond à vos critères de recherche. Essayez de modifier vos filtres.
                    @else
                        Commencez par créer votre premier document pour gérer vos factures et devis.
                    @endif
                </p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('documents.create', 'invoice') }}"
                        class="btn-primary bg-gradient-to-r from-green-500 to-green-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                         Nouveau devis
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>