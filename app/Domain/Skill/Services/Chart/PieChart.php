<?php

namespace App\Domain\Skill\Services\Chart;

use IcehouseVentures\LaravelChartjs\Builder;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Support\Str;

class PieChart extends Charts
{
    public function buildChart(string $name, array $labels, array $data): Builder
    {
        $chartId = 'chart' . Str::studly(Str::slug($name, '_'));
        $dataset = $this->buildDataset($labels, $data);

        return Chartjs::build()
            ->name($chartId)
            ->type('pie')
            ->size(['width' => 500, 'height' => 500])
            ->labels($labels)
            ->datasets($dataset['datasets'])
            ->options($this->chartOptions());
    }

    public function buildDataset(array $labels, array $data): array
    {
        $colors = $this->mapChartColors($data);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Level',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderColor' => $colors,
                ],
            ],
        ];
    }

    public function chartOptions(): array
    {
        return [
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
