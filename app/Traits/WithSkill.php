<?php

namespace App\Traits;

trait WithSkill
{

    public bool $showSkillModal = false;
    public ?int $selectedSkillId = null;
    public ?int $skillLevel = null;
    public ?int $skillYears = null;

    public array $skillsByCategory = [];

    public function addSkill(): void
    {
        $this->validate([
            'selectedSkillId' => ['required', 'exists:skills,id'],
            'skillLevel' => ['required', 'integer', 'min:1', 'max:5'],
            'skillYears' => ['required', 'integer', 'min:0'],
        ]);

        $this->form->skills[$this->selectedSkillId] = [
            'selected' => true,
            'level' => $this->skillLevel,
            'years' => $this->skillYears,
        ];

        // reset modal state
        $this->reset([
            'selectedSkillId',
            'skillLevel',
            'skillYears',
            'showSkillModal',
        ]);
    }
}
