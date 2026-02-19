<?php

namespace App\Domain\Skill\Services;

use App\Domain\Skill\Contracts\SkillAssignable;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Support\Collection;

class SkillService
{
    private Collection $skills;

    public function __construct(
        private readonly ?SkillAssignable $assignable = null
    )
    {
        $this->skills = collect();
    }

    /**
     * Load skills based on user's company fields or all skills
     */
    public function loadSkillsForUser(User $user): self
    {
        if ($user->company) {
            $this->skills = $user->company->fields()
                ->with('categories.skills.category')
                ->get()
                ->flatMap(fn($field) => $field->categories)
                ->flatMap(fn($category) => $category->skills)
                ->unique('id')
                ->values();
        } else {
            $this->skills = Skill::with('category')->get();
        }

        return $this;
    }

    /**
     * Group skills by category name
     */
    public function groupByCategory(): array
    {
        return $this->skills
            ->groupBy(fn($skill) => $skill->category->name)
            ->map(fn($categorySkills) => $categorySkills->map(fn($skill) => [
                'id' => $skill->id,
                'name' => $skill->name,
                'category_type' => $skill->category->type->value,
            ])->toArray())
            ->toArray();
    }


    /**
     * Apply custom filter to skills
     */
    public function filter(callable $callback): self
    {
        $this->skills = $this->skills->filter($callback)->values();
        return $this;
    }

    /**
     * Filter skills by type
     */
    public function filterByType(?string $excludeType = null): self
    {
        if ($excludeType) {
            $this->skills = $this->skills->reject(
                fn($skill) => $skill->category?->type?->value === $excludeType
            );
        }

        return $this;
    }

    /**
     * Get a specific skill by ID from the current collection
     */
    public function findSkill(int $skillId): ?Skill
    {
        return $this->skills->firstWhere('id', $skillId);
    }

    /**
     * Check if skill is a soft skill
     */
    public function isSoftSkill(int $skillId): bool
    {
        $skill = $this->findSkill($skillId);
        return $skill && $skill->isSoftSkill();
    }

    /**
     * Get all skills in the current state
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    /**
     * Get the assignable entity
     */
    public function getAssignable(): ?SkillAssignable
    {
        return $this->assignable;
    }

    /**
     * Set skills manually (useful for testing or custom workflows)
     */
    public function setSkills(Collection $skills): self
    {
        $this->skills = $skills;
        return $this;
    }

}