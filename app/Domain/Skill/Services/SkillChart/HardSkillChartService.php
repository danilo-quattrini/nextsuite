<?php

namespace App\Domain\Skill\Services\SkillChart;


use Illuminate\Support\Collection;

class HardSkillChartService extends SkillChartService
{

    public function getChartData(): Collection
    {
        return $this->buildChartData();
    }

    public function buildChartData(): Collection
    {
        $skills = $this->loadSkills();

        return $skills
            ->flatMap(function ($skill) {
                // Get all fields associated with this skill's category
                $fields = $skill->category?->fields ?? collect();

                // If no fields, put in "Uncategorized"
                if ($fields->isEmpty()) {
                    return collect([
                        ['field' => 'Uncategorized', 'skill' => $skill]
                    ]);
                }

                // Create an entry for each field this skill belongs to
                return $fields->map(fn($field) => [
                    'field' => $field->name,
                    'skill' => $skill
                ]);
            })
            ->groupBy('field')
            ->map(function (Collection $items) {
                // Extract just the skills from each field group
                $skills = $items->pluck('skill');
                return $this->mapSkillGroup($skills);
            })
            ->sortKeys();
    }
}