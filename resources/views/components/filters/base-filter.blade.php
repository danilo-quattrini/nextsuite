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
        <div class="grid grid-cols-4 gap-4 ">
            @foreach($skills as $categoryName => $skillValues)
                <div class="space-y-2 gap-5">
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
</div>
