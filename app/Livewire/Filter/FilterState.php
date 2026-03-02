<?php

namespace App\Livewire\Filter;

use Livewire\Component;

abstract class FilterState extends Component
{

    public array $skills = [];
    public array $selectedSkills = [];
    public string $state = 'all';
    public string $label = 'Skills';
    public string $icon = 'academic-cap';
    public string $dispatchEvent = 'customer-filters-updated';

    public function mount(
        ?string $state = null,
        ?string $label = null,
        ?string $icon = null,
        ?string $dispatchEvent = null
    ): void {
        if ($state !== null) {
            $this->state = $state;
        }

        [$defaultLabel, $defaultIcon] = match ($this->state) {
            'hard' => ['Hard Skill', 'academic-cap'],
            'soft' => ['Soft Skill', 'face-smile'],
            default => ['Skills', 'funnel'],
        };

        $this->label = $label ?? $defaultLabel;
        $this->icon = $icon ?? $defaultIcon;

        if ($dispatchEvent !== null) {
            $this->dispatchEvent = $dispatchEvent;
        }
    }

    abstract function loadSkills(): void;

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

    public function filter(): void
    {
        $this->dispatch($this->dispatchEvent, skillIds: $this->selectedSkills);
    }

    public function clearFilters(): void
    {
        $this->selectedSkills = [];
        $this->dispatch($this->dispatchEvent, skillIds: []);
    }

}
