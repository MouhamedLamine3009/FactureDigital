<x-action-section>
    <x-slot name="title">
        {{ __('Supprimer l\'équipe') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Supprimer définitivement cette équipe.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
            {{ __('Une fois l\'équipe supprimée, toutes ses ressources et ses données seront définitivement supprimées. Avant de supprimer cette équipe, veuillez télécharger les données ou informations que vous souhaitez conserver.') }}
        </div>

        <div class="mt-5">
            <x-danger-button wire:click="$toggle('confirmingTeamDeletion')" wire:loading.attr="disabled">
                {{ __('Supprimer l\'équipe') }}
            </x-danger-button>
        </div>

        <!-- Delete Team Confirmation Modal -->
        <x-confirmation-modal wire:model.live="confirmingTeamDeletion">
            <x-slot name="title">
                {{ __('Supprimer l\'équipe') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Voulez-vous vraiment supprimer cette équipe ? Une fois supprimée, toutes ses ressources et ses données seront définitivement supprimées.') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingTeamDeletion')" wire:loading.attr="disabled">
                    {{ __('Annuler') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteTeam" wire:loading.attr="disabled">
                    {{ __('Supprimer l\'équipe') }}
                </x-danger-button>
            </x-slot>
        </x-confirmation-modal>
    </x-slot>
</x-action-section>
