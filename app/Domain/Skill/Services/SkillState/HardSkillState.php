<?php

namespace App\Domain\Skill\Services\SkillState;

use App\Domain\Skill\Contracts\SkillAssignable;
use App\Models\Skill;
use App\Models\User;

class HardSkillState extends SkillState
{

    /**
     * @inheritDoc
     */
    public function loadSkillsForUser(?User $user = null): self
    {
        $assignable = $this->skillService->getAssignable();

        if ($assignable) {
            $schemaSkillIds = $assignable->skillSchema()
                ->with('skill.category')
                ->get()
                ->filter(fn($schema) => !$schema->skill->isSoftSkill())
                ->pluck('skill_id')
                ->toArray();

            $skills = Skill::with('category')
                ->whereIn('id', $schemaSkillIds)
                ->get();
        } else {
            if ($user && $user->company) {
                $skills = $user->company->fields()
                    ->with('categories.skills.category')
                    ->get()
                    ->flatMap(fn($field) => $field->categories)
                    ->flatMap(fn($category) => $category->skills)
                    ->filter(fn($skill) => !$skill->isSoftSkill())
                    ->unique('id')
                    ->values();
            } else {
                $skills = Skill::with('category')
                    ->whereHas('category', function ($query) {
                        $query->where('type', '!=', 'soft_skill');
                    })
                    ->get();
            }
        }

        $this->skillService->setSkills($skills);

        return $this;
    }

    public function loadSkillFromAssignable(?SkillAssignable $assignable = null): SkillState
    {
        if($assignable === null && !$assignable?->skills()->exists()){
            return $this;
        }

        $skills = $assignable->skills()
            ->with('category')
            ->get()
            ->filter(fn($skill) => !$skill->isSoftSkill())
            ->values();

        $this->skillService->setSkills($skills);
        return $this;
    }
}