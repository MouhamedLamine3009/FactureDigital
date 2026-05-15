<div>
    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 transition-opacity" aria-hidden="true"
                    wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
                    <!-- Success Message -->
                    @if($sent)
                        <div class="text-center py-8">
                            <div
                                class="w-20 h-20 mx-auto mb-6 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center shadow-lg">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Email envoyé !</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">L'email a été envoyé avec succès à {{ $toEmail }}
                            </p>
                            <button wire:click="closeModal" class="btn-primary">
                                Fermer
                            </button>
                        </div>
                    @else
                        <div>
                            <div class="mt-3 sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-xl leading-6 font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-primary-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Envoyer {{ $document->type === 'invoice' ? 'la facture' : 'le devis' }} par email
                                </h3>

                                @if($error)
                                    <div
                                        class="mb-4 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl">
                                        <p class="text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
                                    </div>
                                @endif

                                <form wire:submit.prevent="send" class="space-y-4">
                                    <!-- From -->
                                    <div>
                                        <label for="fromEmail"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">De
                                            <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                                </svg>
                                            </div>
                                            <input type="email" wire:model="fromEmail" id="fromEmail" class="input-modern pl-10"
                                                placeholder="email@exemple.com">
                                        </div>
                                        @error('fromEmail') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- To -->
                                    <div>
                                        <label for="toEmail"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Destinataire
                                            <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                                </svg>
                                            </div>
                                            <input type="email" wire:model="toEmail" id="toEmail" class="input-modern pl-10"
                                                placeholder="email@exemple.com">
                                        </div>
                                        @error('toEmail') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- CC & BCC Row -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="ccEmail"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CC
                                                (copie)</label>
                                            <input type="text" wire:model="ccEmail" id="ccEmail" class="input-modern"
                                                placeholder="email1@ex.com, email2@ex.com">
                                        </div>
                                        <div>
                                            <label for="bccEmail"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">BCC
                                                (copie cachée)</label>
                                            <input type="text" wire:model="bccEmail" id="bccEmail" class="input-modern"
                                                placeholder="email@ex.com">
                                        </div>
                                    </div>

                                    <!-- Subject -->
                                    <div>
                                        <label for="subject"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Objet <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" wire:model="subject" id="subject" class="input-modern">
                                        @error('subject') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Body -->
                                    <div>
                                        <label for="body"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message
                                            <span class="text-red-500">*</span></label>
                                        <textarea wire:model="body" id="body" rows="8" class="input-modern"
                                            placeholder="Votre message..."></textarea>
                                        @error('body') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Attach PDF -->
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model="attachPdf" id="attachPdf"
                                            class="h-5 w-5 text-primary-600 dark:text-primary-400 rounded border-gray-300 dark:border-gray-600 focus:ring-primary-500 dark:focus:ring-primary-400 bg-white dark:bg-gray-700">
                                        <label for="attachPdf" class="ml-3 block text-sm text-gray-700 dark:text-gray-300">
                                            Joindre le PDF ({{ $document->number }})
                                        </label>
                                    </div>

                                    <!-- Preview Link -->
                                    <div class="text-sm">
                                        <a href="{{ route('documents.view-pdf', $document) }}" target="_blank"
                                            class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 underline flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Prévisualiser le PDF →
                                        </a>
                                    </div>

                                    <!-- Actions -->
                                    <div class="mt-6 sm:flex sm:flex-row-reverse gap-3">
                                        <button type="submit" wire:disabled="$sending"
                                            class="btn-primary w-full sm:w-auto mb-3 sm:mb-0">
                                            @if($sending)
                                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                        stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                                Envoi en cours...
                                            @else
                                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                                </svg>
                                                Envoyer l'email
                                            @endif
                                        </button>
                                        <button type="button" wire:click="closeModal" class="btn-secondary w-full sm:w-auto">
                                            Annuler
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>