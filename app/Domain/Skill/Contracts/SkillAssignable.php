<?php

namespace App\Domain\Skill\Contracts;

use App\Models\User;

interface SkillAssignable
{
    public function addSkill(User $user, int $id, int $level, int | null $years): void;
}