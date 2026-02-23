<?php

namespace App\Domain\Skill\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

interface SkillAssignable
{
    /**
     * Get all skills assigned to this entity
     */
    public function skills(): BelongsToMany;

    /**
     * Get the skill schema for this entity
     */
    public function skillSchema(): MorphMany;

    /**
     * Add a skill with level/years
     */
    public function addSkill(User $user, int $id, int $level, int | null $years): void;

    /**
     * Remove a skill
     */
    public function removeSkill(int $skillId): void;
    /**
     * Check if the model has a specific skill assigned
     */
    public function skillExists(int $skillId): bool;

    /**
     * Check if the model has a skill relation
     */
    public function hasSkill(): bool;

    /**
     * Get all the hard skill of the model
     */
    public function getSkills(): Collection;
}