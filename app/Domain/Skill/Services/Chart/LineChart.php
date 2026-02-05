<?php

namespace App\Domain\Skill\Services\Chart;

use IcehouseVentures\LaravelChartjs\Builder;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Support\Str;

class LineChart extends Charts
{
    public function buildChart(string $name, array $labels, array $data): Builder
    {
        $chartId = 'chart' . Str::studly(Str::slug($name, '_'));
        $dataset = $this->buildDataset($labels, $data);

        return Chartjs::build()
            ->name($chartId)
            ->type('line')
            ->size(['width' => 500, 'height' => 500])
            ->labels($labels)
            ->datasets($dataset['datasets'])
            ->options($this->chartOptions());
    }

    public function buildDataset(array $labels, array $data): array
    {
        $colors = $this->mapChartColors($data);
        $lineColor = $colors[0] ?? '#5E81F4';

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Level',
                    'data' => $data,
                    'borderColor' => $lineColor,
                    'backgroundColor' => $lineColor,
                    'pointBackgroundColor' => $colors,
                    'pointBorderColor' => $colors,
                    'tension' => 0.3,
                ],
            ],
        ];
    }

    public function chartOptions(): array
    {
        return [
            'responsive' => true,
            'scales' => [
                'y' => [
                    'min' => 0,
                    'max' => 100,
                    'grid' => [
                        'display' => true,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
