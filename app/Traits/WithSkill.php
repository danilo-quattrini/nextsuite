<?php

namespace App\Traits;

trait WithSkill
{

    public bool $showSkillModal = false;
    public ?int $selectedSkillId = null;
    public ?int $skillLevel = 1;
    public ?int $skillYears = null;


    public array $skillsByCategory = [];

    public function addSkill(): void
    {
        $this->validate([
            'selectedSkillId' => ['required', 'exists:skills,id'],
            'skillLevel' => ['required', 'integer', 'min:1', 'max:100'],
            'skillYears' => ['nullable', 'integer', 'min:0', 'max:30'],
        ]);

        $this->dispatch(
            'skill-selected',
            skillId: $this->selectedSkillId,
            skillLevel: $this->skillLevel,
            skillYears: $this->skillYears
        );

        $this->reset([
            'selectedSkillId',
            'skillLevel',
            'skillYears',
            'showSkillModal',
        ]);
    }
}
