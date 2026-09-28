<div class="animate-fade-in">
    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" wire:click="cancelDelete"></div>

            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Modal Panel -->
                <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <!-- Icon -->
                    <div class="bg-red-50 dark:bg-red-900/30 px-4 py-3 sm:px-6 flex items-center justify-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/50">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">
                                    Confirmer la suppression
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Êtes-vous sûr de vouloir supprimer le client
                                        <strong>{{ $clientNameToDelete }}</strong> ?
                                    </p>
                                    <p class="text-sm text-red-500 dark:text-red-400 mt-2">
                                        Cette action est irréversible. Tous les documents associés ne seront pas supprimés.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                        <button wire:click="delete" type="button"
                            class="inline-flex w-full justify-center rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-colors">
                            Supprimer
                        </button>
                        <button wire:click="cancelDelete" type="button"
                            class="mt-3 inline-flex w-full justify-center rounded-xl bg-white dark:bg-gray-600 px-4 py-2.5 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-500 sm:mt-0 sm:w-auto transition-colors">
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
                    <h1 class="page-title">Clients</h1>
                    <p class="mt-1 text-gray-500 dark:text-gray-400">Gérez votre fichier clients</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <!-- Export CSV -->
                    <button wire:click="exportCsv" class="btn-secondary text-green-600 dark:text-green-400 border-green-200 dark:border-green-700 hover:bg-green-50 dark:hover:bg-green-900/20">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Exporter
                    </button>
                    
                    <!-- Import CSV -->
                    <button wire:click="$toggle('showImportModal')" class="btn-secondary text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/20">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Importer
                    </button>
                    
                    <a href="{{ route('clients.create') }}" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nouveau client
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    @if($showImportModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="$toggle('showImportModal')"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white mb-4" id="modal-title">
                                Importer des clients depuis CSV
                            </h3>
                            <div class="mt-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                    Téléchargez d'abord le modèle CSV pour voir les colonnes attendues.
                                </p>
                                
                                <!-- Download Template -->
                                <button wire:click="downloadTemplate" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 text-sm underline mb-4 block font-medium">
                                    📥 Télécharger le modèle CSV
                                </button>
                                
                                <!-- File Upload -->
                                <label for="import-file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fichier CSV</label>
                                <input type="file" wire:model="importFile" id="import-file" accept=".csv,.txt" class="input-modern file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-50 dark:file:bg-primary-900/50 file:text-primary-700 dark:file:text-primary-300 hover:file:bg-primary-100 dark:hover:file:bg-primary-900">
                                
                                @if($importFile)
                                    <p class="mt-2 text-sm text-green-600 dark:text-green-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Fichier sélectionné: {{ $importFile->getClientOriginalName() }}
                                    </p>
                                @endif
                                
                                @error('importFile')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                
                                <!-- Import Progress -->
                                @if($importing)
                                    <div class="mt-4">
                                        <div class="relative pt-1">
                                            <div class="flex mb-2 items-center justify-between">
                                                <div>
                                                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-primary-600 dark:text-primary-400 bg-primary-200 dark:bg-primary-800">
                                                        Importation en cours
                                                    </span>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-xs font-semibold inline-block text-primary-600 dark:text-primary-400">
                                                        {{ $importProgress }}%
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-primary-200 dark:bg-primary-800">
                                                <div style="width: {{ $importProgress }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-primary-500 transition-all duration-300"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Import Errors -->
                                @if(count($importErrors) > 0)
                                    <div class="mt-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-4">
                                        <h4 class="text-sm font-medium text-red-800 dark:text-red-300">Erreurs d'importation:</h4>
                                        <ul class="mt-2 text-sm text-red-600 dark:text-red-400 max-h-40 overflow-y-auto">
                                            @foreach($importErrors as $error)
                                                <li>• {{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 sm:flex sm:flex-row-reverse sm:gap-3">
                        <button wire:click="importCsv" wire:disabled="!$importFile || $importing" type="button" class="btn-primary w-full sm:w-auto mb-3 sm:mb-0 disabled:opacity-50 disabled:cursor-not-allowed">
                            @if($importing) Importation... @else Importer @endif
                        </button>
                        <button wire:click="$toggle('showImportModal')" type="button" class="btn-secondary w-full sm:w-auto">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <!-- Filters -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-card p-6 mb-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div class="sm:col-span-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rechercher</label>
                    <div class="relative">
                        <input type="text" wire:model.live="search" id="search" class="input-modern pl-10" placeholder="Nom, email ou téléphone...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        @if($search)
                            <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>

                <div class="sm:col-span-1">
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                    <select wire:model.live="type" id="type" class="input-modern">
                        <option value="all">Tous</option>
                        <option value="individual">Particulier</option>
                        <option value="company">Entreprise</option>
                    </select>
                </div>

                <div class="sm:col-span-1">
                    <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Statut</label>
                    <select wire:model.live="is_active" id="is_active" class="input-modern">
                        <option value="all">Tous</option>
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                    </select>
                </div>

                <div class="sm:col-span-1 flex items-end">
                    @if($search || $type !== 'all' || $is_active !== 'all')
                        <button wire:click="resetFilters" class="btn-secondary w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Réinitialiser
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Clients List -->
        @if($clients->count() > 0)
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-card overflow-hidden">
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($clients as $client)
                        <div class="flex items-center justify-between p-5 hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-all duration-200 group">
                            <a href="{{ route('clients.edit', $client) }}" class="flex items-center gap-5 flex-1">
                                <!-- Avatar -->
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-100 dark:from-primary-800 to-primary-200 dark:to-primary-700 flex items-center justify-center text-primary-600 dark:text-primary-300 font-bold text-lg transition-transform group-hover:scale-110">
                                    {{ initials($client->name) }}
                                </div>
                                
                                <!-- Client Info -->
                                <div>
                                    <div class="flex items-center gap-3 mb-1">
                                        <p class="font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors text-lg">
                                            {{ $client->name }}
                                        </p>
                                        @if($client->type === 'company')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300">
                                                Entreprise
                                            </span>
                                        @endif
                                        @if(!$client->is_active)
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300">
                                                Inactif
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            {{ $client->email }}
                                        </span>
                                        @if($client->phone)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                {{ $client->phone }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                            
                            <div class="flex items-center gap-4">
                                <div class="text-right hidden sm:block">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $client->formatted_address ?: 'Aucune adresse' }}</p>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $client->documents_count ?? 0 }} document(s)</p>
                                </div>
                                <!-- Delete Button - Completely outside the link -->
                                <button wire:click="confirmDelete({{ $client->id }})"
                                    class="p-2 text-gray-400 dark:text-gray-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                    title="Supprimer le client">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                <svg class="w-6 h-6 text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6 px-6 pb-6">
                    {{ $clients->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-card p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m102c0-.656-.126- 0v-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Aucun client trouvé</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">
                    @if($search || $type !== 'all' || $is_active !== 'all')
                        Aucun client ne correspond à vos critères de recherche. Essayez de modifier vos filtres.
                    @else
                        Commencez par ajouter votre premier client pour gérer votre fichier.
                    @endif
                </p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('clients.create') }}" class="btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nouveau client
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

