@props(['customerAttributes' => null ])

@if (!empty($customerAttributes))
    <div class="mt-6 space-y-4">
        @foreach ($customerAttributes as $attributeId => $data)
            <div class="flex justify-between items-center border rounded p-4">
                <div>
                    <strong>
                        {{ ucfirst($data['attribute']->name) }}
                    </strong>

                    <div class="text-sm text-primary-grey">
                        Value: {{ $data['value'] }}
                    </div>
                </div>

                <x-button
                        type="button"
                        variant="error"
                        wire:click="removeItem('attributes', {{ (int) $attributeId }})"
                >
                    Remove
                </x-button>
            </div>
        @endforeach
    </div>
@endif