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
            '#5E81F450',
            'rgba(255, 75, 90, 0.50)',
            'rgba(244, 190, 94, 0.50)',
            'rgba(124, 231, 172, 0.50)',
            '#8181a5',
            '#4c5fae',
        ];

        return collect($data)
            ->values()
            ->map(fn ($_, $index) => $palette[$index % count($palette)])
            ->all();
    }
}
