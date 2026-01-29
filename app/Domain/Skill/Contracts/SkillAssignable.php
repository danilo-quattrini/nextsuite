<?php

namespace App\Domain\Skill\Contracts;

interface SkillAssignable
{
    public function addSkill(int $id, int $level, int | null $years): void;
}