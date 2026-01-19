@props(['skills' => [], 'categories' => []])

@if (!empty($skills))
    <div class="mt-6 space-y-4">
        @foreach ($skills as $skillId => $data)
            <div class="flex justify-between items-center border rounded p-4">
                <div>
                    <strong>
                        {{ data_get(collect($categories)
                                    ->collapse()
                                    ->firstWhere('id', (int) $skillId), 'name', 'Skill')
                        }}
                    </strong>
                    <div class="text-sm text-primary-grey">
                        Level: {{ $data['level'] }} —
                        Years: {{ $data['years'] }}
                    </div>
                </div>

                <x-button
                        type="button"
                        variant="error"
                        wire:click="removeItem('skills', {{ (int) $skillId }})"
                >
                    Remove
                </x-button>
            </div>
        @endforeach
    </div>
@endif
