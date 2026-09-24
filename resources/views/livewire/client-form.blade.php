

<div class="animate-fade-in">
    <!-- Header Section -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="page-title flex items-center gap-3">
                        <span
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-100 dark:from-primary-800 to-primary-200 dark:to-primary-700 flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                @if($client)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                @endif
                            </svg>
                        </span>
                        {{ $client ? 'Modifier le client' : 'Nouveau client' }}
                    </h1>
                    <p class="mt-1 text-gray-500 dark:text-gray-400">
                        {{ $client ? 'Mettez à jour les informations du client' : 'Ajoutez un nouveau client à votre fichier' }}
                    </p>
                </div>
                <a href="{{ route('clients.index') }}" class="btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour à la liste
                </a>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <form wire:submit.prevent="save" id="clientForm">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Informations principales -->
                    <div class="glass-card rounded-2xl p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-100 dark:from-primary-800 to-primary-200 dark:to-primary-700 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Informations principales</h3>
                        </div>
                        <div class="space-y-5">
                            <div>
                                <label for="type"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type de
                                    client *</label>
                                <select wire:model="type" id="type" class="input-modern">
                                    <option value="individual">Particulier</option>
                                    <option value="company">Entreprise</option>
                                </select>
                                @error('type') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom
                                    *</label>
                                <input type="text" wire:model="name" id="name" class="input-modern"
                                    placeholder="Nom complet ou raison sociale">
                                @error('name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="contact_name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom du
                                    contact</label>
                                <input type="text" wire:model="contact_name" id="contact_name" class="input-modern"
                                    placeholder="Personne à contacter">
                            </div>

                            <div>
                                <label for="email"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                <input type="email" wire:model="email" id="email" class="input-modern"
                                    placeholder="email@exemple.com">
                                @error('email') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}
                                </p> @enderror
                            </div>

                            <div>
                                <label for="phone"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Téléphone</label>
                                <input type="text" wire:model="phone" id="phone" class="input-modern"
                                    placeholder="+221 70 123 45 67">
                            </div>
                        </div>
                    </div>

                    <!-- Adresse -->
                    <div class="glass-card rounded-2xl p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-100 dark:from-blue-800 to-blue-200 dark:to-blue-700 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Adresse</h3>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label for="address"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Adresse</label>
                                <input type="text" wire:model="address" id="address" class="input-modern"
                                    placeholder="Rue, numéro">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="postal_code"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Code
                                        postal</label>
                                    <input type="text" wire:model="postal_code" id="postal_code" class="input-modern"
                                        placeholder="BP 12345">
                                </div>

                                <div>
                                    <label for="city"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ville</label>
                                    <input type="text" wire:model="city" id="city" class="input-modern"
                                        placeholder="Dakar">
                                </div>
                            </div>

                            <div>
                                <label for="country"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pays</label>
                                <input type="text" wire:model="country" id="country" class="input-modern"
                                    placeholder="Sénégal">
                            </div>
                        </div>
                    </div>

                    <!-- Informations fiscales -->
                    <div class="glass-card rounded-2xl p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-100 dark:from-amber-800 to-amber-200 dark:to-amber-700 flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Informations fiscales</h3>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label for="ninea"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NINEA</label>
                                <input type="text" wire:model="ninea" id="ninea" class="input-modern"
                                    placeholder="123456789">
                            </div>

                            <div>
                                <label for="vat_number"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Numéro
                                    TVA</label>
                                <input type="text" wire:model="vat_number" id="vat_number" class="input-modern"
                                    placeholder="SN12345678901">
                            </div>
                        </div>
                    </div>

                    <!-- Paiement -->
                    <div class="glass-card rounded-2xl p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-100 dark:from-green-800 to-green-200 dark:to-green-700 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Paiement</h3>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label for="payment_method"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mode de
                                    paiement préféré</label>
                                <select wire:model="payment_method" id="payment_method" class="input-modern">
                                    <option value="">Sélectionner</option>
                                    <option value="bank_transfer">Virement bancaire</option>
                                    <option value="check">Chèque</option>
                                    <option value="cash">Espèces</option>
                                    <option value="credit_card">Carte de crédit</option>
                                </select>
                            </div>

                            <div>
                                <label for="payment_terms"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Conditions
                                    de paiement (jours)</label>
                                <input type="number" wire:model="payment_terms" id="payment_terms" min="0"
                                    class="input-modern" placeholder="30">
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="glass-card rounded-2xl p-6 lg:col-span-2">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-100 dark:from-purple-800 to-purple-200 dark:to-purple-700 flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Notes</h3>
                        </div>

                        <div>
                            <label for="notes"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes
                                supplémentaires</label>
                            <textarea wire:model="notes" id="notes" rows="3" class="input-modern"
                                placeholder="Informations complémentaires..."></textarea>
                        </div>

                        <div class="mt-5">
                            <label class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" wire:model="is_active" id="is_active" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 dark:bg-gray-600 rounded-full shadow-inner transition-colors duration-200 peer-checked:bg-green-500">
                                    </div>
                                    <div class="absolute w-5 h-5 bg-white rounded-full shadow top-0.5 left-0.5 transition-transform duration-200 peer-checked:translate-x-5"
                                        style="width: 20px; height: 20px; border-radius: 50%; top: 2px;"></div>
                                </div>
                                <span class="ml-3 text-sm text-gray-600 dark:text-gray-400 font-medium">Client
                                    actif</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('clients.index') }}" class="btn-secondary order-2 sm:order-1">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Annuler
                    </a>
<button type="button" wire:click="save" class="btn-primary order-1 sm:order-2" wire:loading.attr="disabled" wire:target="save" title="Créer le client" id="saveClientBtn">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($client)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            @endif
                        </svg>
                        {{ $client ? 'Mettre à jour' : 'Créer le client' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
