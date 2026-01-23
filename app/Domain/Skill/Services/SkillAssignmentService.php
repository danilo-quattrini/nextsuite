<?php

namespace App\Domain\Skill\Services;

use App\Domain\Skill\Contracts\SkillAssignable;

class SkillAssignmentService
{
    /**
     * Assign a skill to any model that implements the SkillAssignable interface
    **/
    public function assign(
        SkillAssignable $model,
        int $skillId,
        int $skillLevel,
        int $skillYears
    ): void {
        $model->addSkill($skillId, $skillLevel, $skillYears);
    }

    /**
     * Assign an array of skills to any model that implements the SkillAssignable interface
     **/
    public function assignMany(
        SkillAssignable $model,
        array $skills)
    : void {
        foreach ($skills as $id => $value) {
            $this->assign(
                $model,
                (int) $id,
                (int) ($value['level'] ?? 0),
                (int) ($value['years'] ?? 0)
            );
        }
    }
}
