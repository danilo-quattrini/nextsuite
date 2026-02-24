<?php

namespace App\Domain\Skill\Services\SkillChart;

use Illuminate\Support\Collection;

class SoftSkillChartService extends  SkillChartService
{

    public function getChartData(): Collection
    {
        return $this->buildChartData();
    }

    public function buildChartData(): Collection
    {
        $skills = $this->loadSkills();

        return $skills
            ->groupBy(fn($skill) => $skill->category?->name ?? 'Uncategorized')
            ->map(fn(Collection $skills) => $this->mapSkillGroup($skills))
            ->sortKeys();
    }
}