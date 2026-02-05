<?php

namespace App\Domain\Skill\Services;

use App\Models\Customer;
use Illuminate\Support\Collection;

class FieldSkillChartService
{
    public function getFieldSkills(Customer $customer): array
    {
        return $this->buildFieldSkills($customer)->toArray();
    }

    public function buildFieldSkills(Customer $customer): Collection
    {
        $skills = $customer->load('skills.category.fields')->skills;

        return $skills
            ->flatMap(function ($skill) {
                $fields = $skill->category?->fields ?? collect();

                if ($fields->isEmpty()) {
                    return collect([['field' => 'Uncategorized', 'skill' => $skill]]);
                }

                return $fields->map(fn ($field) => ['field' => $field->name, 'skill' => $skill]);
            })
            ->groupBy('field')
            ->map(function (Collection $items) {
                $skillsList = $items
                    ->map(function (array $item) {
                        $skill = $item['skill'];

                        return [
                            'name' => $skill->name,
                            'level' => $skill->pivot?->level,
                        ];
                    })
                    ->values();

                $average = $skillsList
                    ->pluck('level')
                    ->filter(fn ($level) => is_numeric($level))
                    ->avg();

                return [
                    'skills' => $skillsList,
                    'average' => $average,
                ];
            })
            ->sortKeys();
    }

    public function overallAverage(Collection $skillsByField): ?float
    {
        $allLevels = $skillsByField
            ->flatMap(fn (array $group) => collect($group['skills'])->pluck('level'))
            ->filter(fn ($level) => is_numeric($level));

        return $allLevels->isEmpty() ? null : $allLevels->avg();
    }
}
