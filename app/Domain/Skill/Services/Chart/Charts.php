<?php

namespace App\Domain\Skill\Services\Chart;

use IcehouseVentures\LaravelChartjs\Builder;

abstract class Charts
{
    abstract public function buildChart(string $name, array $labels, array $data): Builder;
    abstract public function buildDataset(array $labels, array $data): array;
    abstract public function chartOptions(): array;

    public function mapChartColors(array $data): array
    {
        $palette = [
            'rgba(0, 200, 255, 0.65)',
            'rgba(255, 98, 64, 0.65)',
            'rgba(255, 196, 0, 0.65)',
            'rgba(0, 214, 143, 0.65)',
            'rgba(255, 92, 168, 0.65)',
            'rgba(122, 92, 255, 0.65)',
            'rgba(46, 213, 255, 0.65)',
            'rgba(255, 140, 0, 0.65)',
        ];

        return collect($data)
            ->values()
            ->map(fn ($_, $index) => $palette[$index % count($palette)])
            ->all();
    }
}
