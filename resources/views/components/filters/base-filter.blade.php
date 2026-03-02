<?php

use App\Domain\Skill\Services\SkillService;
use App\Domain\Skill\Services\SkillState\AllSkillState;
use App\Domain\Skill\Services\SkillState\HardSkillState;
use App\Domain\Skill\Services\SkillState\SoftSkillState;
use App\Livewire\Filter\FilterState;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\Lazy;

new #[Lazy]
class extends FilterState {

    #[NoReturn]
    public function loadSkills(): void
    {
        $skillService = match (true) {
            $this->state === 'hard' => new SkillService(null, new HardSkillState()),
            $this->state ===  'soft' => new SkillService(null, new SoftSkillState()),
            default => new SkillService(null, new AllSkillState())
        };
        $skillService->loadAllSkills();
        $this->skills = $skillService->groupByCategory();
    }
};
?>

<div class="flex items-center justify-start gap-4">
    <x-form.dropdown-button
            width="w-100"
    >
        <x-slot:trigger>
            <x-button
                    variant="white"
                    size="auto"
                    wire:click="loadSkills"
            >
                <x-heroicon name="{{ $icon }}"/>
                {{ $label }}
            </x-button>
        </x-slot:trigger>
        <x-slot:content>
            <div class="flex justify-between items-center my-2">
                <x-button
                        variant="outline-error"
                        size="auto"
                        wire:click="clearFilters"
                >
                    <x-heroicon name="trash"/>
                    Clear
                </x-button>
                <x-button
                        size="auto"
                        wire:click="filter"
                >
                    <x-heroicon name="funnel"/>
                    Filter
                </x-button>
            </div>
            @if(!empty($skills))
                <div class="space-y-3">
                    @foreach($skills as $categoryName => $skillValues)
                        <div class="space-y-2 border-b border-outline-grey/60 pb-3 last:border-b-0">
                            <p class="text-xs uppercase tracking-wide text-primary-grey">
                                {{ $categoryName }}
                            </p>
                            @php
                                $maxNameLength = 0;
                                foreach ($skillValues as $skill) {
                                    $maxNameLength = max($maxNameLength, strlen($skill['name'] ?? ''));
                                }

                                $columns = $maxNameLength >= 24 ? 4 : ($maxNameLength >= 16 ? 3 : 2);
                            @endphp
                            <div @class([
                                'grid gap-2',
                                'grid-cols-2' => $columns === 2,
                                'grid-cols-3' => $columns === 3,
                                'grid-cols-4' => $columns === 4,
                            ])>
                                @foreach($skillValues as $skill)
                                    @php
                                        $skillId = $skill['id'];
                                        $isSelected = in_array($skillId, $selectedSkills, true);
                                    @endphp
                                    <button
                                            type="button"
                                            value="{{ $skillId }}"
                                            wire:click="toggleSkill({{ $skillId }})"
                                            class="px-4 py-1.5 cursor-pointer rounded-md text-sm font-medium border transition
                                            truncate text-nowrap
                                                {{ $isSelected
                                                    ? 'bg-primary text-white border-primary'
                                                    : 'bg-white text-black border-outline-grey hover:bg-gray-100'
                                                }}"
                                    >
                                        {{ $skill['name'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-slot:content>
    </x-form.dropdown-button>
</div>
