<?php

namespace App\Services\Charts\Skill;

use App\Models\Customer;
use Illuminate\Support\Collection;

class SoftSkillChartService
{
    public function getSoftSkills(Customer $customer): array
    {
        return $this->buildSoftSkills($customer)->toArray();
    }

    public function buildSoftSkills(Customer $customer, string $type = 'soft_skill'): Collection
    {
        return $this->getCustomerFilteredSkills($customer, $type)
            ->map(fn (Collection $skills) => $this->mapSkillGroup($skills))
            ->sortKeys();
    }

    public function overallAverage(Collection $skillsByCategory): ?float
    {
        $allLevels = $skillsByCategory
            ->flatMap(fn (array $group) => collect($group['skills'])->pluck('level'))
            ->filter(fn ($level) => is_numeric($level));

        return $allLevels->isEmpty() ? null : $allLevels->avg();
    }

    private function mapSkillGroup(Collection $skills): array
    {
        $skillsList = $skills
            ->map(fn ($skill) => $this->formatSkill($skill))
            ->values();

        return [
            'skills' => $skillsList,
            'average' => $this->skillAverage($skillsList),
        ];
    }

    private function formatSkill($skill): array
    {
        return [
            'name' => $skill->name,
            'level' => $skill->pivot?->level,
        ];
    }

    private function skillAverage(Collection $skill): ?float
    {
        $average = $skill
            ->pluck('level')
            ->filter(fn ($level) => is_numeric($level))
            ->avg();

        return $average === null ? null : (float) $average;
    }

    private function getCustomerFilteredSkills(Customer $customer, string $type = 'soft_skill'): Collection
    {
        return $customer->load('skills.category')
            ->skills
            ->filter(fn ($skill) => $skill->category?->type?->value === $type)
            ->groupBy(fn ($skill) => $skill->category?->name ?? 'Uncategorized');
    }
}
