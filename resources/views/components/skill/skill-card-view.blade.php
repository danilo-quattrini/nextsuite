@props(['skills' => []])

@if (!empty($skills))
    <div class="mt-6 grid grid-cols-1 gap-4">
        @foreach ($skills as $skillId => $data)
            <div class="flex flex-col gap-3 border border-outline-grey rounded-md p-4 bg-white">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-xs uppercase tracking-wide text-primary-grey">Skill</div>
                        <div class="font-semibold text-black">
                            Selected skill
                        </div>
                    </div>
                    <x-button
                            type="button"
                            size="auto"
                            variant="error"
                            wire:click="removeItem('skills', {{ (int) $skillId }})"
                    >
                        <x-heroicon class="text-white" name="trash" />
                    </x-button>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 rounded-full bg-outline-grey text-sm text-black">
                        Level: {{ $data['level'] }}
                    </span>
                    @if($data['years'] != null)
                        <span class="px-3 py-1 rounded-full bg-outline-grey text-sm text-black">
                            Years: {{ $data['years'] }}
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
