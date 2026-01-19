<div
        class="document-generation-container"
        wire:poll.3s="checkStatus"
        @if(in_array($status, ['completed', 'failed'])) wire:poll.stop @endif
        xmlns:x-slot="http://www.w3.org/1999/html">
    <x-popup-box modal="showModalDocumentGenerationStatus">
        @if(in_array($status, ['processing', 'pending']))
           <x-slot:header>
               <div class="popup-icon-header bg-secondary rounded-full border border-primary">
                   <x-heroicon size="lg"  class="text-primary" name="document-text" variant="outline" />
               </div>
           </x-slot:header>

            <x-slot:subheader>
                <p>Generating Document...</p>
            </x-slot:subheader>

            <x-slot:message>
               Your document will be generated in few second
            </x-slot:message>

            <div class="w-full mt-4">
                <div class="progress-wrapper">
                    <div
                            class="progress-bar-custom"
                            style="width: {{ $progress }}%;"
                    ></div>
                </div>

                <p class="text-sm text-primary-grey mt-2 text-center">
                    {{ $progress }}%
                </p>
            </div>
        @elseif($status === 'completed')

            <x-slot:header>
                <div class="popup-icon-header bg-secondary-success-100 rounded-full border border-secondary-success">
                    <x-heroicon size="md" class="text-secondary-success" name="document-check" variant="outline" />
                </div>
            </x-slot:header>

            <x-slot:subheader>
                <p>Done!</p>
            </x-slot:subheader>
            <x-slot:message>
                Your document it's ready to be downloaded
            </x-slot:message>
            <x-button href="{{ $documentUrl }}" size="full">
                Download
                <x-heroicon size="sm" name="arrow-down-tray" />
            </x-button>

        @elseif($status === 'failed')
            <x-slot:header>
                <div class="popup-icon-header bg-secondary-error-100 rounded-full border border-secondary-error">
                    <x-heroicon size="md"  class="text-secondary-error" name="x-mark" variant="outline" />
                </div>
            </x-slot:header>
            <x-slot:subheader>
                <p>Oh no!</p>
            </x-slot:subheader>
            <x-slot:message>
                Your document it's broken
            </x-slot:message>
        @endif
    </x-popup-box>
</div>