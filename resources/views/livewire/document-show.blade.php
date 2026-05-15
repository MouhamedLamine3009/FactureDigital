<div class="animate-fade-in">
    @livewire('document-email', ['document' => $document])

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('documents.index') }}" class="p-2.5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <div class="flex items-center gap-3">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $document->type === 'quote' ? 'Devis' : 'Facture' }} {{ $document->number }}
                            </h1>
                            <span class="px-3 py-1.5 text-sm font-medium rounded-full {{
                                $document->status === 'paid' ? 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300' :
                                ($document->status === 'draft' ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' :
                                ($document->status === 'sent' ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' :
                                ($document->status === 'viewed' ? 'bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300' :
                                ($document->status === 'accepted' ? 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300' :
                                ($document->status === 'refused' ? 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300' :
                                ($document->status === 'overdue' ? 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300' :
                                ($document->status === 'cancelled' ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' :
                                'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300')))))))
                            }}">
                                @if($document->status === 'draft') Brouillon
                                @elseif($document->status === 'sent') Envoyé
                                @elseif($document->status === 'viewed') Vu
                                @elseif($document->status === 'accepted') Accepté
                                @elseif($document->status === 'refused') Refusé
                                @elseif($document->status === 'paid') Payé
                                @elseif($document->status === 'partial_paid') Partiellement payé
                                @elseif($document->status === 'overdue') En retard
                                @elseif($document->status === 'cancelled') Annulé
                                @else {{ ucfirst($document->status) }}
                                @endif
                            </span>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 mt-1">Créé le {{ $document->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <!-- Download PDF -->
                    <a href="{{ route('documents.pdf', $document) }}" class="btn-secondary p-2.5" title="Télécharger PDF">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </a>

                    <!-- View PDF -->
                    <a href="{{ route('documents.view-pdf', $document) }}" target="_blank" class="btn-secondary p-2.5" title="Voir PDF">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>

                    <!-- Email -->
                    <button wire:click="$dispatch('open-email-modal')" class="btn-secondary p-2.5 text-blue-600 dark:text-blue-400" title="Envoyer par email">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </button>

                    @if($document->status === 'draft')
                        <button wire:click="markAsSent" class="btn-primary p-2.5" title="Marquer comme envoyé">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    @endif

                    @if(in_array($document->status, ['sent', 'viewed', 'partial_paid']))
                        <button wire:click="openPaymentModal" class="btn-primary bg-gradient-to-r from-green-500 to-green-600 p-2.5" title="Enregistrer un paiement">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    @endif

                    @if($document->type === 'quote' && !$document->hasBeenConverted())
                        <form action="{{ route('documents.convert', $document) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn-secondary p-2.5 text-indigo-600 dark:text-indigo-400" title="Convertir en facture">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </button>
                        </form>
                    @endif

                    @if($document->type === 'quote' && $document->hasBeenConverted())
                        <a href="{{ route('documents.show', $document->getConvertedDocument()) }}" class="btn-secondary p-2.5 text-green-600 dark:text-green-400" title="Voir la facture">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    @endif

                    @if($document->status !== 'paid' && $document->status !== 'cancelled')
                        <a href="{{ route('documents.edit', $document) }}" class="btn-secondary p-2.5 text-indigo-600 dark:text-indigo-400" title="Modifier">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                    @endif

                    @if($document->type === 'quote' && in_array($document->status, ['sent', 'viewed']))
                        <button wire:click="markAsAccepted" class="btn-secondary p-2.5 text-green-600 dark:text-green-400" title="Accepter">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                        <button wire:click="markAsRefused" class="btn-secondary p-2.5 text-red-600 dark:text-red-400" title="Refuser">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif

                    @if($document->status !== 'cancelled' && $document->status !== 'paid')
                        <button type="button"
                                class="btn-secondary p-2.5 text-gray-600 dark:text-gray-400"
                                title="Annuler"
                                onclick="if (confirm('Êtes-vous sûr de vouloir annuler ce document ?')) { $wire.markAsCancelled(); } return false;"
                                >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Company & Client Info -->
                <div class="glass-card rounded-2xl p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Company Info -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Émetteur
                            </h3>
                            <p class="font-bold text-lg text-gray-900 dark:text-white">{{ $company->legal_name ?: $company->name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $company->address }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $company->postal_code }} {{ $company->city }}</p>
                            @if($company->email)
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $company->email }}</p>
                            @endif
                            @if($company->phone)
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $company->phone }}</p>
                            @endif
                        </div>
                        <!-- Client Info -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Client
                            </h3>
                            <p class="font-bold text-lg text-gray-900 dark:text-white">{{ $client->name }}</p>
                            @if($client->contact_name)
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $client->contact_name }}</p>
                            @endif
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $client->address }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $client->postal_code }} {{ $client->city }}</p>
                            @if($client->email)
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $client->email }}</p>
                            @endif
                            @if($client->phone)
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $client->phone }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Document Dates -->
                <div class="glass-card rounded-2xl p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase mb-1">Type</p>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $document->type === 'quote' ? 'Devis' : 'Facture' }}</p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase mb-1">Numéro</p>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $document->number }}</p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase mb-1">Date d'émission</p>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $document->issue_date->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-center p-4 {{ $document->status === 'overdue' ? 'bg-red-50 dark:bg-red-900/30' : 'bg-gray-50 dark:bg-gray-700/50' }} rounded-xl">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase mb-1">Date d'échéance</p>
                            <p class="font-bold {{ $document->status === 'overdue' ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                {{ $document->due_date->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="glass-card rounded-2xl overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Qté</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Prix unitaire</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">TVA</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($items as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $item->description }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white text-right">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white text-right">
                                        {{ number_format($item->unit_price, 2, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white text-right">{{ $item->tax_rate }}%</td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white text-right">
                                        {{ number_format($item->total, 2, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Notes & Terms -->
                @if($document->notes || $document->terms_conditions)
                    <div class="glass-card rounded-2xl p-6">
                        @if($document->notes)
                            <div class="mb-4">
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Notes
                                </h3>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $document->notes }}</p>
                            </div>
                        @endif
                        @if($document->terms_conditions)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Conditions de règlement
                                </h3>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $document->terms_conditions }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Totals -->
                <div class="glass-card rounded-2xl p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Totaux
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Sous-total HT</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ number_format($document->subtotal, 2, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</span>
                        </div>
                        @if($document->discount_amount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Remise</span>
                                <span class="text-green-600 dark:text-green-400 font-medium">- {{ number_format($document->discount_amount, 2, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">TVA</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ number_format($document->tax_amount, 2, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</span>
                        </div>
                        <div class="border-t pt-3 flex justify-between">
                            <span class="font-bold text-gray-900 dark:text-white">Total TTC</span>
                            <span class="font-bold text-xl text-primary-600 dark:text-primary-400">{{ number_format($document->total, 2, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</span>
                        </div>
                        @if($document->paid_amount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Déjà payé</span>
                                <span class="text-green-600 dark:text-green-400 font-medium">- {{ number_format($document->paid_amount, 2, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</span>
                            </div>
                            <div class="border-t pt-3 flex justify-between">
                                <span class="font-bold text-gray-900 dark:text-white">Reste à payer</span>
                                <span class="font-bold text-lg text-red-600 dark:text-red-400">{{ number_format($document->balance, 2, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment History -->
                <div class="glass-card rounded-2xl p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Historique des paiements
                    </h3>
                    @if($payments->count() > 0)
                        <ul class="space-y-3">
                            @foreach($payments as $payment)
                                <li class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-bold text-gray-900 dark:text-white">{{ number_format($payment->amount, 2, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $payment->payment_date->format('d/m/Y') }}</p>
                                            @if($payment->method)
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($payment->method) }}</p>
                                            @endif
                                        </div>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300">Payé</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-6">
                            <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Aucun paiement enregistré</p>
                        </div>
                    @endif
                </div>

                <!-- Timeline -->
                <div class="glass-card rounded-2xl p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Historique
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-3 w-3 rounded-full bg-primary-500 mt-1.5 ring-4 ring-primary-100 dark:ring-primary-900"></div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Document créé</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $document->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @if($document->sent_at)
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-3 w-3 rounded-full bg-blue-500 mt-1.5 ring-4 ring-blue-100 dark:ring-blue-900"></div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Document envoyé</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $document->sent_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                        @if($document->viewed_at)
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-3 w-3 rounded-full bg-purple-500 mt-1.5 ring-4 ring-purple-100 dark:ring-purple-900"></div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Document consulté</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $document->viewed_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                        @if($document->paid_at)
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-3 w-3 rounded-full bg-green-500 mt-1.5 ring-4 ring-green-100 dark:ring-green-900"></div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Document payé</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $document->paid_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    @if($showPaymentModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-900 dark:bg-opacity-80" aria-hidden="true" wire:click="closePaymentModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div>
                        <div class="mt-3 sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl leading-6 font-bold text-gray-900 dark:text-white mb-6" id="modal-title">
                                Enregistrer un paiement
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="paymentAmount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Montant</label>
                                    <div class="relative rounded-xl">
                                        <input type="number" name="paymentAmount" id="paymentAmount" step="0.01" min="0.01" wire:model="paymentAmount" class="input-modern pr-12" placeholder="0.00">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">{{ $company->currency_symbol ?? 'FCFA' }}</span>
                                        </div>
                                    </div>
                                    @error('paymentAmount') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Reste à payer: {{ number_format($document->balance, 2, ',', ' ') }} {{ $company->currency_symbol ?? 'FCFA' }}</p>
                                </div>

                                <div>
                                    <label for="paymentMethod" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mode de paiement</label>
                                    <select id="paymentMethod" name="paymentMethod" wire:model="paymentMethod" class="input-modern">
                                        <option value="bank_transfer">Virement Bancaire</option>
                                        <option value="check">Chèque</option>
                                        <option value="cash">Espèces</option>
                                        <option value="credit_card">Carte de Crédit</option>
                                        <option value="online">Paiement en Ligne</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="paymentDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date du paiement</label>
                                    <input type="date" name="paymentDate" id="paymentDate" wire:model="paymentDate" class="input-modern">
                                </div>

                                <div>
                                    <label for="paymentNotes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes (optionnel)</label>
                                    <textarea id="paymentNotes" name="paymentNotes" rows="3" wire:model="paymentNotes" class="input-modern" placeholder="Notes complémentaires..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 sm:flex sm:flex-row-reverse sm:gap-3">
                        <button type="button" wire:click="recordPayment" class="btn-primary w-full sm:w-auto mb-3 sm:mb-0 bg-gradient-to-r from-green-500 to-green-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Enregistrer le paiement
                        </button>
                        <button type="button" wire:click="closePaymentModal" class="btn-secondary w-full sm:w-auto">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
