<?php

namespace App\Domain\Skill\Services;

use App\Domain\Skill\Contracts\SkillAssignable;
use App\Models\Skill;
use App\Models\User;

class SkillAssignmentService
{
    /**
     * Assign a skill to any model that implements the SkillAssignable interface
    **/
    public function assign(
        SkillAssignable $model,
        User $evaluator,
        int $skillId,
        int $skillLevel,
        int | null $skillYears
    ): void {
        $model->addSkill($evaluator, $skillId, $skillLevel, $skillYears);
        activity()
            ->performedOn($model)
            ->causedBy($evaluator)
            ->withProperties([
                'name' => Skill::with('category')->find($skillId)->name,
                'level' => $skillLevel
            ])
            ->log('add new skill');
    }

    /**
     * Assign an array of skills to any model that implements the SkillAssignable interface
     **/
    public function assignMany(
        SkillAssignable $model,
        User $evaluator,
        array $skills)
    : void {
        foreach ($skills as $id => $value) {
            $this->assign(
                $model,
                $evaluator,
                (int) $id,
                (int) ($value['level'] ?? 0),
                (int) ($value['years'] ?? 0)
            );
        }
    }
}
