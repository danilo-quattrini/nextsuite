<?php

namespace App\Domain\Skill\Services\Chart;

use IcehouseVentures\LaravelChartjs\Builder;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Support\Str;

class BarChart extends Charts
{

    public function mapChartColors(array $data): array
    {
        return array_map(function ($value) {
            if ($value === 0) {
                return '#d1d5db'; // gray — No level
            } elseif ($value < 50) {
                return 'oklch(70% 0.22 20)'; // red — Bad
            } elseif ($value < 100) {
                return 'oklch(90% 0.14 75)'; // yellow — Good
            } else {
                return 'oklch(85% 0.12 155)'; // green — Expert
            }
        }, $data);
    }

    public function buildChart(string $name, array $labels, array $data): Builder
    {
        $chartId = 'chart' . Str::studly(Str::slug($name, '_'));
        $dataset = $this->buildDataset($labels, $data);

        return Chartjs::build()
            ->name($chartId)
            ->type("bar")
            ->size(["width" => 500, "height" => 500])
            ->labels($labels)
            ->datasets($dataset['datasets'])
            ->options($this->chartOptions());
    }

    public function buildDataset(array $labels, array $data): array
    {
        $barColors = $this->mapChartColors($data);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    "barThickness" => 30,
                    "minBarLength" => 0,
                    "label" => "Level",
                    "data" => $data,
                    "backgroundColor" => $barColors,
                    "borderColor" => $barColors,
                    "categoryPercentage" => 0.9,
                    "barPercentage" => 1.0,
                ],
            ],
        ];
    }


    public function chartOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'responsive' => true,
            'layout' => [
                'padding' => [
                    'top' => 15,
                    'bottom' => 15,
                ],
            ],
            'elements' => [
                'bar' => [
                    'borderWidth' => 0.5,
                ],
            ],
            'scales' => [
                'y' => [
                    'min' => 0,
                    'max' => 100,
                    'ticks' => [
                        'stepSize' => 1,
                        'display' => false,
                    ],
                    'grid' => [
                        'display' => false,
                    ],
                    'border' => [
                        'display' => false,
                    ],
                ],
                'x' => [
                    'min' => 0,
                    'max' => 100,
                    'ticks' => [
                        'display' => false,
                    ],
                    'grid' => [
                        'display' => false,
                    ],
                    'border' => [
                        'display' => false,
                    ],
                ],
            ],
            'plugins' => [
                'softSkillBarLabels' => [
                    'labelColor' => '#8181a5',
                    'valueColor' => '#1c1d21',
                    'fontSize' => 16,
                    'valueFontSize' => 24,
                ],
                'legend' => [
                    'display' => false,
                ],
            ],
        ];

    }
}
