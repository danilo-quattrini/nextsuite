<?php

use App\Domain\Skill\Services\SkillService;
use App\Domain\Skill\Services\SkillState\AllSkillState;
use App\Domain\Skill\Services\SkillState\HardSkillState;
use App\Domain\Skill\Services\SkillState\SoftSkillState;
use App\Livewire\Filter\FilterState;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Component;

new #[Lazy]
class extends Component {

    public array $skills = [];
    public array $selectedSkills = [];
    public int $visibleSection = 2;
    public function toggleSkill(int $skillId): void
    {
        if (in_array($skillId, $this->selectedSkills, true)) {
            $this->selectedSkills = array_values(
                array_diff($this->selectedSkills, [$skillId])
            );
            return;
        }

        $this->selectedSkills[] = $skillId;
    }

    public function showMore(): void
    {
        $this->visibleSection += count($this->skills) - $this->visibleSection;
    }

    public function showLess(): void
    {
        $this->visibleSection = 2;
    }
    #[On('send-selected-skill')]
    public function sendSelectedSkills(string $roleToSearch): void
    {
        $this->dispatch('filter-customer', roleToSearch: $roleToSearch, skillIds: $this->selectedSkills);
    }

    #[On('clear-selected-skill')]
    public function clear(): void
    {
        $this->selectedSkills = [];
    }
};
?>
<div class="flex items-center justify-start gap-4">
    @if(!empty($skills))
        <div class="flex flex-col gap-4">
            @foreach(array_slice($skills, 0, $visibleSection) as $categoryName => $skillValues)
                <x-card.card-container
                        title="{{ $categoryName }}"
                        card-size="sm"
                        size="sm"
                >
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
                                    class="btn-white text-sm rounded-md p-2 cursor-pointer truncate text-nowrap
                                        {{ $isSelected
                                            ? ' text-primary '
                                            : 'bg-white text-black border-outline-grey'
                                        }}"
                            >
                                {{ $skill['name'] }}
                            </button>
                        @endforeach
                    </div>
                </x-card.card-container>
            @endforeach

            @if(count($skills) > $visibleSection)
                    <button
                            wire:click.preserve-scroll="showMore"
                            wire:transition
                            @click.stop
                            class="text-xs text-primary-grey hover:text-primary cursor-pointer transition-colors mt-1 px-2"
                    >
                        Show more ({{ count($skills) - $visibleSection }} remaining)
                    </button>
                @else
                    <button
                            wire:click.preserve-scroll="showLess"
                            wire:transition
                            @click.stop
                            class="text-xs text-primary-grey hover:text-primary cursor-pointer transition-colors mt-1 px-2"
                    >
                        Show less
                    </button>
            @endif
        </div>
    @endif
</div>
