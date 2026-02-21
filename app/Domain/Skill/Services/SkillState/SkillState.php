<?php

namespace App\Domain\Skill\Services\SkillState;

use App\Domain\Skill\Services\SkillService;
use App\Models\User;
use Illuminate\Support\Collection;

abstract class SkillState
{
    protected SkillService $skillService;

    public function setContext(SkillService $skillService): void
    {
        $this->skillService = $skillService;
    }

    /**
     * Load skills based on state-specific logic
     */
    abstract public function loadSkillsForUser(?User $user = null): self;

    /**
     * Get the loaded skills
     */
    public function getSkills(): Collection
    {
        return $this->skillService->getSkills();
    }

    /**
     * Transition to a different state
     */
    public function transitionTo(SkillState $newState): void
    {
        $this->skillService->transitionToState($newState);
    }
}