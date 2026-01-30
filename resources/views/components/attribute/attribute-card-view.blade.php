@props(['customerAttributes' => null ])

@if (!empty($customerAttributes))
    <div class="mt-6 grid grid-cols-1 gap-4">
        @foreach ($customerAttributes as $attributeId => $data)
            <div class="flex flex-col gap-3 border border-outline-grey rounded-md p-4 bg-white">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-xs uppercase tracking-wide text-primary-grey">Attribute</div>
                        <div class="font-semibold text-black">
                            {{ ucfirst($data['attribute']->name) }}
                        </div>
                    </div>
                    <x-button
                            type="button"
                            size="auto"
                            variant="error"
                            wire:click="removeItem('attributes', {{ (int) $attributeId }})"
                    >
                        <x-heroicon class="text-white" name="trash" />
                    </x-button>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-primary-grey">Value</span>
                    <span class="px-3 py-1 rounded-full bg-outline-grey text-sm text-black">
                        {{ ucfirst($data['value']) }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>
@endif
