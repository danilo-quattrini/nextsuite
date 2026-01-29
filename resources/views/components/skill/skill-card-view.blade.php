@props(['skills' => []])

@if (!empty($skills))
    <div class="mt-6">
        <div class="flex gap-4 overflow-x-auto justify-end pb-2 pr-1">
            @foreach ($skills as $skillId => $data)
                <div class="flex min-w-[220px] items-start justify-between gap-4 border rounded p-4 shrink-0">
                    <div>
                        <strong>
                            Skill
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
    </div>
@endif
