@props([
    '$showDeleteModal' => false
])
@if($showDeleteModal)
    {{-- WARNING POP --}}
    <x-popup-box modal="showDeleteModal">
        <x-slot:header>
            <div class="w-16 h-16 flex justify-center items-center bg-secondary-error-100 rounded-full border border-secondary-error">
                <x-heroicon size="lg"  class="text-secondary-error" name="exclamation-triangle" variant="solid" />
            </div>
        </x-slot:header>
        <x-slot:subheader>
            Are you sure?
        </x-slot:subheader>

        <x-slot:message>
            This action cannot be undone.
        </x-slot:message>

        <div class="flex gap-4">
            <x-button
                    variant="disable"
                    size="large"
                    wire:click="$set('showDeleteModal', false)"
            >
                Cancel
            </x-button>

            <x-button
                    variant="error"
                    size="large"
                    wire:click="deleteModelElement"
            >
                Delete
            </x-button>
        </div>
    </x-popup-box>
@endif
