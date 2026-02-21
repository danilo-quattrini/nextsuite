<?php

namespace App\Domain\Skill\Services\SkillState;

use App\Domain\Skill\Services\SkillState\SkillState;
use App\Models\Skill;
use App\Models\User;

class AllSkillState extends SkillState
{

    /**
     * @inheritDoc
     */
    public function loadSkillsForUser(?User $user = null): self
    {
        $assignable = $this->skillService->getAssignable();

        // If assignable exists, load from their schema
        if ($assignable) {
            $schemaSkillIds = $assignable->skillSchema()
                ->pluck('skill_id')
                ->toArray();

            $skills =   Skill::with('category')
                ->whereIn('id', $schemaSkillIds)
                ->get();
        }
        // Otherwise, load all skills from company/system
        else {
            if ($user && $user->company) {
                $skills = $user->company->fields()
                    ->with('categories.skills.category')
                    ->get()
                    ->flatMap(fn($field) => $field->categories)
                    ->flatMap(fn($category) => $category->skills)
                    ->unique('id')
                    ->values();
            } else {
                $skills = Skill::with('category')->get();
            }
        }

        $this->skillService->setSkills($skills);

        return $this;
    }
}