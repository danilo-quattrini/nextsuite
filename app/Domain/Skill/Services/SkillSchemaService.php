<?php

namespace App\Domain\Skill\Services;

use App\Domain\Skill\Contracts\SkillAssignable;
use App\Models\SkillSchema;
use App\Models\User;

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
    /**
     * Define/Update skill schema for a SkillAssignable entity
     * */
    public function updateUserSchema(): self
    {
        $this->ensureAssignableIsSet();

        $skillsSchemaIds = $this->assignable->skillSchema()->pluck('skill_id')->toArray();
        $newSkillIds = array_column($this->skillsData, 'skill_id');

        $this->removeSkillsFromSkillSchema($skillsSchemaIds, $newSkillIds);
        $this->updateOrCreateSchema();

        return $this;
    }

    /**
     * Apply the schema to the assignable entity (create actual skill assignments)
     * @param  User  $evaluator  The user performing the evaluation/assignment
     * @param bool $overwriteExisting Whether to overwrite existing skill assignments
     */
    public function applySchemaToAssignable(
        User $evaluator,
        bool $overwriteExisting = false
    ): self
    {
        $this->ensureAssignableIsSet();

        $schema = $this->assignable->skillSchema()->with('skill')->get();

        foreach ($schema as $schemaEntry) {
            $skill = $schemaEntry->skill;

            if (!$overwriteExisting && $this->assignable->hasSkill($skill->id)) {
                continue;
            }

            // Apply the skill based on schema defaults
            $this->assignable->addSkill(
                user: $evaluator,
                id: $skill->id,
                level: $schemaEntry->default_level,
                years: null
            );
        }
        return  $this;
    }

    /**
     * Remove skills from schemas that are no longer included
     **/
    private function removeSkillsFromSkillSchema(
        array $currentSkillsSchemaIds,
        array $newSkillIds
    ): void
    {
        $skillsToRemove = array_diff($currentSkillsSchemaIds, $newSkillIds);

        if(empty($skillsToRemove)){
            return;
        }

        SkillSchema::removeBulk($this->assignable, $skillsToRemove);
    }

    /**
     * Add or remove new schemas entries (information)
     **/
    private function updateOrCreateSchema():void
    {
        foreach ($this->skillsData as $skillData) {
            $this->assignable->skillSchema()->updateOrCreate(
                ['skill_id' => $skillData['skill_id']],
                [
                    'default_level' => $skillData['default_level'] ?? 1,
                ]
            );
        }
    }

    /**
     * Ensure assignable is set before operations
     */
    private function ensureAssignableIsSet(): void
    {
        if ($this->assignable === null) {
            throw new \LogicException(
                'Assignable entity not set. Call for($assignable, $skillsData) first.'
            );
        }
    }
}