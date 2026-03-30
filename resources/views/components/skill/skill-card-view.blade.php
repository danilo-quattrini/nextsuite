@props(['skills' => [], 'hideSoftSkills' => false])

@if (!empty($skills))
    <div class="mt-6 grid grid-cols-1 gap-4">
        @foreach ($skills as $skillId => $data)
            @if($data['selected'] && !($hideSoftSkills && ($data['skill']['type'] ?? null) === 'soft_skill'))
                <div class="flex flex-col gap-3 border border-outline-grey rounded-md p-4 bg-white">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-xs uppercase tracking-wide text-primary-grey">Skill</div>
                            <div class="font-semibold text-black">
                                {{ $data['skill']['name'] }}
                            </div>
                        </div>
                        <x-button
                                size="icon"
                                variant="error"
                                wire:click="removeItem('skills', {{ (int) $skillId }})"
                        >
                            <x-heroicon name="trash" />
                        </x-button>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <x-tag
                                variant="white"
                                size="sm"
                        >
                            Level: {{ $data['level'] }}
                        </x-tag>
                        @if($data['years'] != null)
                            <x-tag
                                    variant="white"
                                    size="sm"
                            >
                                Years: {{ $data['years'] }}
                            </x-tag>
                        @endif
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endif
