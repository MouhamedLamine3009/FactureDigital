@php
    use App\Services\WhatsApp\WhatsAppLinkService;
    $linkService = app(WhatsAppLinkService::class);
    $waMeLink = $linkService->generateLink($document);
    $phoneFormatted = $linkService->formatPhoneNumber($toPhone);
    $hasValidPhone = $toPhone && $linkService->isValidPhone($toPhone);
@endphp

<div>
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div>
                        <h3 class="text-xl leading-6 font-bold text-gray-900 dark:text-white mb-6" id="modal-title">
                            Envoyer par WhatsApp
                        </h3>

                        <div class="space-y-4">
                            <!-- Numéro de téléphone -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Téléphone WhatsApp <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="toPhone" class="input-modern w-full" placeholder="221771234567 (format international)" />
                                @if($toPhone && !$hasValidPhone)
                                    <p class="mt-1 text-sm text-amber-500">
                                        Format attendu : indicateur pays + numéro (ex: 221771234567)
                                    </p>
                                @endif
                                @error('toPhone')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Message -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message</label>
                                <textarea wire:model="body" rows="5" class="input-modern w-full" placeholder="Votre message..."></textarea>
                                @error('body')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Aperçu du message envoyé -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3 text-sm text-gray-600 dark:text-gray-300">
                                <p class="font-medium text-gray-700 dark:text-gray-200 mb-1">Le message contiendra aussi :</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Le lien sécurisé de consultation PDF</li>
                                    <li>Le nom du client : <strong>{{ $document->client?->name ?? 'N/A' }}</strong></li>
                                    <li>{{ $document->type === 'quote' ? 'Devis' : 'Facture' }} N° <strong>{{ $document->number }}</strong></li>
                                    <li>Montant TTC : <strong>{{ number_format($document->total, 0, ',', ' ') }} FCFA</strong></li>
                                </ul>
                            </div>

                            <!-- Messages d'erreur / succès -->
                            @if($error)
                                <div class="bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300 rounded-xl p-3 text-sm">
                                    {{ $error }}
                                </div>
                            @endif

                            @if($sent)
                                <div class="bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-300 rounded-xl p-3 text-sm">
                                    Message WhatsApp envoyé avec succès.
                                </div>
                            @endif
                        </div>

                        <!-- Boutons d'action -->
                        <div class="mt-6 space-y-3">
                            <!-- MÉTHODE 1 : Lien wa.me (Recommandée) -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-3 border border-blue-200 dark:border-blue-800">
                                <p class="text-xs font-medium text-blue-700 dark:text-blue-300 mb-2 flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Méthode 1 – Recommandation : Lien rapide wa.me
                                </p>
                                <a href="{{ $waMeLink }}"
                                   target="_blank"
                                   class="btn-primary w-full justify-center bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 inline-flex items-center"
                                   onclick="if(!{{ $hasValidPhone ? 'true' : 'false' }}) { alert('Veuillez saisir un numéro de téléphone valide.'); return false; }">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    Ouvrir dans WhatsApp
                                </a>
                                <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                    S'ouvre dans WhatsApp Web/Desktop. Le client confirme l'envoi.
                                </p>
                            </div>

                            <!-- Boutons du bas -->
                            <div class="flex gap-2">
                                @if($metaConfigured)
                                    <!-- METHODE 2 : Envoi automatique via API Meta -->
                                    <button type="button"
                                            wire:click="sendViaApi"
                                            class="btn-primary w-full sm:w-auto bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 inline-flex items-center justify-center"
                                            @disabled($sending)>
                                        @if($sending)
                                            <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                            Envoi en cours...
                                        @else
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                            </svg>
                                            Envoyer automatiquement (API)
                                        @endif
                                    </button>
                                @else
                                    <button type="button"
                                            wire:click="openWhatsApp"
                                            class="btn-primary w-full sm:w-auto bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 inline-flex items-center justify-center"
                                            @disabled(!$hasValidPhone)>
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                        </svg>
                                        Ouvrir WhatsApp
                                    </button>
                                @endif

                                <button type="button" wire:click="closeModal" class="btn-secondary w-full sm:w-auto" @disabled($sending)>
                                    Fermer
                                </button>
                            </div>

                            @if(!$metaConfigured)
                                <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                                    Astuce : Configurez META_WHATSAPP_ACCESS_TOKEN dans le .env pour activer l'envoi automatique (Méthode 2).
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Script pour ouvrir WhatsApp dans un nouvel onglet -->
    @script
    <script>
        window.addEventListener('open-whatsapp-link', event => {
            window.open(event.detail.url, '_blank');
        });
    </script>
    @endscript
</div>

