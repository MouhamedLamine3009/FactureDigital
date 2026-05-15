<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Paramètres de l'entreprise</h1>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form wire:submit.prevent="save" enctype="multipart/form-data">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Colonne de gauche -->
                    <div class="space-y-6">
                        <!-- Informations générales -->
                        <div class="glass-card rounded-2xl p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informations générales
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom de
                                        l'entreprise *</label>
                                    <input type="text" wire:model="name" id="name" class="input-modern">
                                    @error('name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}
                                    </p> @enderror
                                </div>

                                <div>
                                    <label for="legal_name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Raison
                                        sociale</label>
                                    <input type="text" wire:model="legal_name" id="legal_name" class="input-modern">
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="ninea"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">NINEA</label>
                                        <input type="text" wire:model="ninea" id="ninea" class="input-modern"
                                            placeholder="12345678 A 9">
                                    </div>

                                    <div>
                                        <label for="rccm"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">RCCM</label>
                                        <input type="text" wire:model="rccm" id="rccm" class="input-modern"
                                            placeholder="SN/DKR/2026/A/1234">
                                    </div>

                                    <div>
                                        <label for="vat_number"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Numéro
                                            TVA</label>
                                        <input type="text" wire:model="vat_number" id="vat_number" class="input-modern"
                                            placeholder="SN12345678901">
                                    </div>

                                    <div class="flex items-center pt-6">
                                        <input type="checkbox" wire:model="vat_applicable" id="vat_applicable"
                                            class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 dark:text-indigo-500 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500">
                                        <label for="vat_applicable"
                                            class="ml-2 text-sm text-gray-600 dark:text-gray-400">TVA applicable</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Coordonnées -->
                        <div class="glass-card rounded-2xl p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Coordonnées</h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="address"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Adresse</label>
                                    <input type="text" wire:model="address" id="address" class="input-modern">
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="postal_code"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Code
                                            postal</label>
                                        <input type="text" wire:model="postal_code" id="postal_code"
                                            class="input-modern">
                                    </div>

                                    <div>
                                        <label for="city"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ville</label>
                                        <input type="text" wire:model="city" id="city" class="input-modern">
                                    </div>

                                    <div>
                                        <label for="country"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pays</label>
                                        <input type="text" wire:model="country" id="country" class="input-modern"
                                            value="Sénégal">
                                    </div>

                                    <div>
                                        <label for="phone"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Téléphone</label>
                                        <input type="text" wire:model="phone" id="phone" class="input-modern"
                                            placeholder="+221 77 123 XX XX">
                                    </div>

                                    <div>
                                        <label for="email"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                        <input type="email" wire:model="email" id="email" class="input-modern">
                                    </div>

                                    <div>
                                        <label for="website"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Site
                                            web</label>
                                        <input type="text" wire:model="website" id="website" class="input-modern"
                                            placeholder="https://...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Devise -->
                        <div class="glass-card rounded-2xl p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Devise</h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="currency"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Devise</label>
                                    <select wire:model="currency" id="currency" class="input-modern">
                                        <option value="XOF">XOF (CFA)</option>
                                        <option value="EUR">EUR (Euro)</option>
                                        <option value="USD">USD (Dollar)</option>
                                        <option value="GBP">GBP (Livre)</option>
                                        <option value="MAD">MAD (Dirham)</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="currency_symbol"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Symbole</label>
                                    <input type="text" wire:model="currency_symbol" id="currency_symbol"
                                        class="input-modern" placeholder="FCFA">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne de droite -->
                    <div class="space-y-6">
                        <!-- Logo et signature -->
                        <div class="glass-card rounded-2xl p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Logo et signature</h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="logo"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo</label>
                                    <input type="file" wire:model="logo" id="logo" accept="image/*"
                                        class="input-modern">
                                    @if($company->logo_path)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($company->logo_path) }}" alt="Logo"
                                                class="h-20 w-auto">
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <label for="signature"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Signature</label>
                                    <input type="file" wire:model="signature" id="signature" accept="image/*"
                                        class="input-modern">
                                    @if($company->signature_path)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($company->signature_path) }}" alt="Signature"
                                                class="h-20 w-auto">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Numérotation des documents -->
                        <div class="glass-card rounded-2xl p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Numérotation des
                                documents</h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="invoice_prefix"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Préfixe
                                        facture</label>
                                    <input type="text" wire:model="invoice_prefix" id="invoice_prefix"
                                        class="input-modern">
                                </div>

                                <div>
                                    <label for="invoice_next_number"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prochain
                                        numéro</label>
                                    <input type="number" wire:model="invoice_next_number" id="invoice_next_number"
                                        min="1" class="input-modern">
                                </div>

                                <div>
                                    <label for="quote_prefix"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Préfixe
                                        devis</label>
                                    <input type="text" wire:model="quote_prefix" id="quote_prefix" class="input-modern">
                                </div>

                                <div>
                                    <label for="quote_next_number"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prochain
                                        numéro</label>
                                    <input type="number" wire:model="quote_next_number" id="quote_next_number" min="1"
                                        class="input-modern">
                                </div>
                            </div>
                        </div>

                        <!-- Coordonnées bancaires -->
                        <div class="glass-card rounded-2xl p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Coordonnées bancaires
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="bank_name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom de la
                                        banque</label>
                                    <input type="text" wire:model="bank_name" id="bank_name" class="input-modern">
                                </div>

                                <div>
                                    <label for="bank_account_name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Intitulé du
                                        compte</label>
                                    <input type="text" wire:model="bank_account_name" id="bank_account_name"
                                        class="input-modern" placeholder="Nom du titulaire du compte">
                                </div>

                                <div>
                                    <label for="bank_account_number"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Numéro de
                                        compte</label>
                                    <input type="text" wire:model="bank_account_number" id="bank_account_number"
                                        class="input-modern" placeholder="Numéro de compte bancaire">
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="bank_iban"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">IBAN</label>
                                        <input type="text" wire:model="bank_iban" id="bank_iban" class="input-modern">
                                    </div>

                                    <div>
                                        <label for="bank_bic"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">BIC/SWIFT</label>
                                        <input type="text" wire:model="bank_bic" id="bank_bic" class="input-modern">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modèles de documents - Pleine largeur -->
                <div class="mt-6 glass-card rounded-2xl p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Modèles de documents</h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <label for="terms_conditions"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Conditions de
                                règlement</label>
                            <textarea wire:model="terms_conditions" id="terms_conditions" rows="4" class="input-modern"
                                placeholder="Ex: Paiement sous 30 jours..."></textarea>
                        </div>

                        <div>
                            <label for="footer_notes"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes de pied de
                                page</label>
                            <textarea wire:model="footer_notes" id="footer_notes" rows="4" class="input-modern"
                                placeholder="Ex: Merci pour votre confiance..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="mt-6 flex justify-end">
                    <button type="button" wire:click="save" wire:loading.attr="disabled"
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition disabled:opacity-50">
                        <span wire:loading.remove>Enregistrer les paramètres</span>
                        <span wire:loading>Chargement...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div x-data="{ show: @entangle('showConfirmModal') }" x-show="show"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @keydown.escape.window="show = false"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        style="display: none;" x-init="@this.on('show-success', () => { show = false; })">

        <div x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4" @click.stop
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center transform transition-all">

            <div
                class="mx-auto w-20 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mb-6 shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Confirmer l'enregistrement</h3>

            <p class="text-gray-600 dark:text-gray-300 mb-6">Voulez-vous vraiment enregistrer les paramètres de
                l'entreprise ?</p>

            <div class="flex gap-3">
                <button @click="show = false"
                    class="flex-1 px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-xl transition-all duration-200">
                    Annuler
                </button>
                <button wire:click="confirmSave" @click="show = false"
                    class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md">
                    Confirmer
                </button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div x-data="{ show: false, message: '' }" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="show = false"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        style="display: none;"
        x-init="@this.on('show-success', (data) => { message = data.message; show = true; setTimeout(() => { show = false }, 3000); })">

        <div @click.stop x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center transform transition-all">

            <div
                class="mx-auto w-20 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mb-6 shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Succès !</h3>

            <p class="text-gray-600 dark:text-gray-300 mb-6" x-text="message"></p>

            <button @click="show = false"
                class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md">
                OK
            </button>
        </div>
    </div>
</div>