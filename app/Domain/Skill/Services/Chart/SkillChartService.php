<?php

namespace App\Domain\Skill\Services\Chart;

use App\Domain\Skill\Contracts\SkillAssignable;
use App\Domain\Skill\Services\SkillService;
use App\Domain\Skill\Services\SkillState\AllSkillState;
use App\Domain\Skill\Services\SkillState\HardSkillState;
use App\Domain\Skill\Services\SkillState\SkillState;
use App\Domain\Skill\Services\SkillState\SoftSkillState;
use Illuminate\Support\Collection;
use InvalidArgumentException;

abstract  class SkillChartService
{

    protected SkillService $skillService;

    public function __construct(
        private readonly ?SkillAssignable $assignable = null,
        private readonly ?string $skillChartService = 'all'
    )
    {
        $this->skillService = new SkillService(
            assignable: $this->assignable,
            state: static::makeState($this->skillChartService)
        );
    }

    /**
     * Factory method to create skill state based on type
     */
    public static function makeState(string $type): SkillState
    {
        $normalizedType = strtolower($type);

        return match ($normalizedType) {
            'hard' => new HardSkillState(),
            'soft' => new SoftSkillState(),
            'all' => new AllSkillState(),
            default => throw new InvalidArgumentException(
                'Unsupported skill type: [' . $type . ']. Supported skills: ' . implode(', ', self::supportedTypes())
            ),
        };
    }

    /**
     * All the skill chart services supported
     * */
    public static function supportedTypes(): array
    {
        return ['soft', 'all', 'hard'];
    }

    /**
     * Load all the skill owned by the assignable
     * */
    public function loadSkills(): Collection
    {
        $this->skillService->loadSkillFromAssignable();
        return $this->skillService->getSkills();
    }

    /**
     * Get chart data as array
     */
    abstract public function getChartData(): Collection;

    /**
     * Build the chart data structure
     */
    abstract public function buildChartData(): Collection;

    /**
     * Calculate overall average across all groups
     */
    public function overallAverage(Collection $groupedSkills): ?float
    {
        $allLevels = $groupedSkills
            ->flatMap(fn(array $group) => collect($group['skills'])->pluck('level'))
            ->filter(fn($level) => is_numeric($level));

        return $allLevels->isEmpty() ? null : round($allLevels->avg(), 2);
    }

    /**
     * Calculate average level for a collection of skills
     */
    protected function calculateAverage(Collection $skills): ?float
    {
        $levels = $skills
            ->pluck('level')
            ->filter(fn($level) => is_numeric($level));

        return $levels->isEmpty() ? null : round($levels->avg(), 1);
    }

    /**
     * Map a group of skills to chart a format
     */
    protected function mapSkillGroup(Collection $skills): array
    {
        $skillsList = $skills
            ->map(fn($skill) => $this->formatSkill($skill))
            ->values();

        return [
            'skills' => $skillsList,
            'average' => $this->calculateAverage($skillsList),
        ];
    }

    /**
     * Format a single skill for chart display
     */
    protected function formatSkill($skill): array
    {
        return [
            'name' => $skill->name,
            'level' => $skill->pivot?->level,
        ];
    }
}