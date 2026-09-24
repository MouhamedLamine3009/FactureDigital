<div class="animate-fade-in">
    <!-- Header Section -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $document ? 'Modifier' : 'Nouveau' }} {{ $type === 'quote' ? 'devis' : 'facture' }}
                    </h1>
                    <p class="mt-1 text-gray-500 dark:text-gray-400">Remplissez les informations ci-dessous</p>
                </div>
                <a href="{{ route('documents.index') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-500 transition-colors">
                    Fermer
                </a>
            </div>
        </div>
    </div>

    <!-- Wizard Steps -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-4 shadow-card">
            <div class="flex items-center justify-between">
                @php
                    $steps = [
                        1 => ['title' => 'Client'],
                        2 => ['title' => 'Articles'],
                        3 => ['title' => 'Paramètres'],
                        4 => ['title' => 'Aperçu'],
                    ];
                @endphp

                @foreach($steps as $step => $info)
                    <div class="flex items-center {{ $loop->last ? '' : 'flex-1' }}">
                        <button wire:click="goToStep({{ $step }})" type="button"
                            class="flex items-center transition-colors duration-200 {{ $currentStep >= $step ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 dark:text-gray-500' }}">
                            <div
                                class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300 transform 
                                        {{ $currentStep > $step ? 'bg-gradient-to-r from-green-500 to-green-600 text-white scale-110' : ($currentStep == $step ? 'bg-gradient-to-r from-primary-500 to-primary-600 text-white scale-105' : 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400') }}">
                                @if($currentStep > $step)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    {{ $step }}
                                @endif
                            </div>
                            <span class="ml-2 text-sm font-medium hidden sm:inline">{{ $info['title'] }}</span>
                        </button>
                        @if(!$loop->last)
                            <div
                                class="flex-1 h-1 mx-4 rounded-full transition-colors duration-300 {{ $currentStep > $step ? 'bg-gradient-to-r from-green-500 to-primary-500' : 'bg-gray-200 dark:bg-gray-700' }}">
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- STEP 1: Client -->
                    @if($currentStep === 1)
                        <div class="glass-card rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Sélectionner un client
                            </h3>
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Client
                                    <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="client_id" class="input-modern">
                                    <option value="">Sélectionner un client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                                @error('client_id') <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}
                                </p> @enderror
                            </div>

                            @if($client_id)
                                @php $selectedClient = $clients->firstWhere('id', $client_id); @endphp
                                @if($selectedClient)
                                    <div
                                        class="p-4 bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/30 dark:to-primary-800/20 rounded-xl border border-primary-100 dark:border-primary-800">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-bold text-lg">
                                                {{ strtoupper(substr($selectedClient->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-white">{{ $selectedClient->name }}
                                                </p>
                                                @if($selectedClient->email)
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedClient->email }}</p>
                                                @endif
                                                @if($selectedClient->phone)
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedClient->phone }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="flex justify-between">
                            <a href="{{ route('documents.index') }}" class="btn-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Annuler
                            </a>
                            @if($currentStep < $totalSteps)
                                <button type="button" wire:click="nextStep" class="btn-primary">
                                    Suivant
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    @endif

                    <!-- STEP 2: Items -->
                    @if($currentStep === 2)
                        <div class="glass-card rounded-2xl p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    Articles / Services
                                </h3>
                                <button type="button" wire:click="addItem" class="btn-primary text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Ajouter
                                </button>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                                            <th
                                                class="px-3 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">
                                                Description</th>
                                            <th
                                                class="px-3 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase w-28">
                                                Qté</th>
                                            <th
                                                class="px-3 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase w-36">
                                                Prix Unitaire</th>
                                            <th
                                                class="px-3 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase w-20">
                                                TVA</th>
                                            <th
                                                class="px-3 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase w-32">
                                                Total</th>
                                            <th class="px-3 py-3 w-12"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $index => $item)
                                            <tr
                                                class="border-t dark:border-gray-600 hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                                <td class="px-3 py-3">
                                                    <input type="text" wire:model="items.{{ $index }}.description"
                                                        class="input-modern text-sm" placeholder="Description *">
                                                </td>
                                                <td class="px-3 py-3">
                                                    <input type="number" wire:model.lazy="items.{{ $index }}.quantity"
                                                        wire:input="calculateTotals" step="0.01" min="0.01"
                                                        class="input-modern text-sm text-center font-semibold w-full"
                                                        style="min-width:70px;">
                                                </td>
                                                <td class="px-3 py-3">
                                                    <input type="number" wire:model.lazy="items.{{ $index }}.unit_price"
                                                        wire:input="calculateTotals" step="0.01" min="0"
                                                        class="input-modern text-sm text-center font-semibold w-full"
                                                        style="min-width:90px;">
                                                </td>
                                                <td class="px-3 py-3">
                                                    <select wire:model="items.{{ $index }}.tax_rate"
                                                        wire:change="calculateTotals" class="input-modern text-sm">
                                                        <option value="0">0%</option>
                                                        <option value="18">18%</option>
                                                    </select>
                                                </td>
                                                <td class="px-3 py-3 font-bold text-gray-900 dark:text-white">
                                                    {{ number_format($this->getItemTotal($item), 2) }}
                                                </td>
                                                <td class="px-3 py-3">
                                                    @if(count($items) > 1)
                                                        <button type="button" wire:click="removeItem({{ $index }})"
                                                            class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div
                                class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl flex justify-between items-center">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Sous-total:</span>
                                <span
                                    class="font-bold text-lg text-primary-600 dark:text-primary-400">{{ number_format($subtotal, 2) }}
                                    {{ $currency_symbol }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" wire:click="previousStep" class="btn-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Précédent
                            </button>
                            @if($currentStep < $totalSteps)
                                <button type="button" wire:click="nextStep" class="btn-primary">
                                    Suivant
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    @endif

                    <!-- STEP 3: Settings -->
                    @if($currentStep === 3)
                        <div class="space-y-6">
                            <div class="glass-card rounded-2xl p-6">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Dates et paiement
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date
                                            d'émission <span class="text-red-500">*</span></label>
                                        <input type="date" wire:model="issue_date" class="input-modern">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date
                                            d'échéance <span class="text-red-500">*</span></label>
                                        <input type="date" wire:model="due_date" class="input-modern">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Paiement
                                            (jours)</label>
                                        <input type="number" wire:model="payment_terms" min="0" class="input-modern"
                                            placeholder="30">
                                    </div>
                                </div>
                            </div>

                            <div class="glass-card rounded-2xl p-6">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Notes et conditions
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                                        <textarea wire:model="notes" rows="3" class="input-modern"
                                            placeholder="Notes visibles sur le document..."></textarea>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Conditions
                                            de règlement</label>
                                        <textarea wire:model="terms_conditions" rows="3" class="input-modern"
                                            placeholder="Conditions de paiement..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" wire:click="previousStep" class="btn-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Précédent
                            </button>
                            <button type="button" wire:click="nextStep" class="btn-primary">
                                Suivant
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    @endif

                    <!-- STEP 4: Preview -->
                    @if($currentStep === 4)
                        @php $preview = $this->getPreviewData(); @endphp
                        <div class="glass-card rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Aperçu du document
                            </h3>

                            @if($preview['client'])
                                <div
                                    class="mb-6 p-4 bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/30 dark:to-primary-800/20 rounded-xl border border-primary-100 dark:border-primary-800">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $preview['client']->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $preview['client']->email }}</p>
                                </div>
                            @endif

                            <table class="w-full mb-6">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">
                                            Description</th>
                                        <th
                                            class="px-3 py-2 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">
                                            Qté</th>
                                        <th
                                            class="px-3 py-2 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">
                                            Prix</th>
                                        <th
                                            class="px-3 py-2 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">
                                            TVA</th>
                                        <th
                                            class="px-3 py-2 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">
                                            Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($preview['items'] as $item)
                                        <tr class="border-t dark:border-gray-600">
                                            <td class="px-3 py-3 text-gray-900 dark:text-white">{{ $item['description'] }}</td>
                                            <td class="px-3 py-3 text-right text-gray-700 dark:text-gray-300">
                                                {{ $item['quantity'] }}</td>
                                            <td class="px-3 py-3 text-right text-gray-700 dark:text-gray-300">
                                                {{ number_format($item['unit_price'], 2) }}</td>
                                            <td class="px-3 py-3 text-right text-gray-700 dark:text-gray-300">
                                                {{ $item['tax_rate'] }}%</td>
                                            <td class="px-3 py-3 text-right font-medium text-gray-900 dark:text-white">
                                                {{ number_format($this->getItemTotal($item), 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="flex justify-end">
                                <div class="w-64 space-y-2">
                                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                        <span>Sous-total:</span>
                                        <span>{{ number_format($preview['subtotal'], 2) }} {{ $currency_symbol }}</span>
                                    </div>
                                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                        <span>TVA:</span>
                                        <span>{{ number_format($preview['tax'], 2) }} {{ $currency_symbol }}</span>
                                    </div>
                                    <div
                                        class="flex justify-between border-t pt-2 font-bold text-lg text-gray-900 dark:text-white">
                                        <span>Total TTC:</span>
                                        <span
                                            class="text-primary-600 dark:text-primary-400">{{ number_format($preview['total'], 2) }}
                                            {{ $currency_symbol }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" wire:click="previousStep" class="btn-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Précédent
                            </button>
                            <button type="submit"
                                class="btn-primary bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $document ? 'Mettre à jour' : 'Créer le document' }}
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Sidebar - Totals -->
                <div>
                    <div class="glass-card rounded-2xl p-6 sticky top-24">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Totaux
                        </h3>

                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Sous-total HT</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ number_format($subtotal, 2) }}
                                    {{ $currency_symbol }}</span>
                            </div>
                            @if($discount > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Remise</span>
                                    <span
                                        class="text-green-600 dark:text-green-400">-{{ number_format($discount, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">TVA</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ number_format($tax, 2) }}
                                    {{ $currency_symbol }}</span>
                            </div>
                            <div class="border-t pt-3 flex justify-between">
                                <span class="font-bold text-gray-900 dark:text-white">Total TTC</span>
                                <span
                                    class="font-bold text-xl text-primary-600 dark:text-primary-400">{{ number_format($total, 2) }}
                                    {{ $currency_symbol }}</span>
                            </div>
                        </div>

                        <!-- Progress indicator -->
                        <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-2">
                                <span>Progression</span>
                                <span>{{ round(($currentStep / $totalSteps) * 100) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-gradient-to-r from-primary-500 to-primary-600 h-2 rounded-full transition-all duration-300"
                                    style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>