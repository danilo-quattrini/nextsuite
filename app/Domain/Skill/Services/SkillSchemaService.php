<?php

namespace App\Domain\Skill\Services;

use App\Domain\Skill\Contracts\SkillAssignable;

class SkillSchemaService
{
    private SkillAssignable|null $assignable = null;
    private array $skillsData = [];

    /**
     * For Dependency Injection in Laravel
     */
    public function __construct() {}

    public function for(SkillAssignable $assignable, array $skillsData): self
    {
        $this->assignable = $assignable;
        $this->skillsData = $skillsData;
        return $this;
    }

}