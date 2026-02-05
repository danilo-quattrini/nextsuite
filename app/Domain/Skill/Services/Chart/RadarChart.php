<?php

namespace App\Domain\Skill\Services\Chart;

use IcehouseVentures\LaravelChartjs\Builder;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Support\Str;

class RadarChart extends Charts
{
    public function buildChart(string $name, array $labels, array $data): Builder
    {
        $chartId = 'chart' . Str::studly(Str::slug($name, '_'));
        $dataset = $this->buildDataset($labels, $data);

        return Chartjs::build()
            ->name($chartId)
            ->type('radar')
            ->size(['width' => 500, 'height' => 500])
            ->labels($labels)
            ->datasets($dataset['datasets'])
            ->options($this->chartOptions());
    }

    public function buildDataset(array $labels, array $data): array
    {
        $colors = $this->mapChartColors($data);
        $lineColor = $colors[0] ?? '#5E81F4';
        $backgroundColor = $this->transparentColor($lineColor);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Level',
                    'data' => $data,
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => $lineColor,
                    'pointBackgroundColor' => $colors,
                    'pointBorderColor' => $colors,
                ],
            ],
        ];
    }

    public function chartOptions(): array
    {
        return [
            'responsive' => true,
            'scales' => [
                'r' => [
                    'min' => 0,
                    'max' => 100,
                    'ticks' => [
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

    private function transparentColor(string $color): string
    {
        if (str_starts_with($color, 'rgba(')) {
            return preg_replace('/rgba\\(([^,]+),([^,]+),([^,]+),[^\\)]+\\)/', 'rgba($1,$2,$3,0.2)', $color) ?? $color;
        }

        if (str_starts_with($color, 'rgb(')) {
            return preg_replace('/rgb\\(([^,]+),([^,]+),([^\\)]+)\\)/', 'rgba($1,$2,$3,0.2)', $color) ?? $color;
        }

        if (str_starts_with($color, '#') && strlen($color) === 7) {
            $red = hexdec(substr($color, 1, 2));
            $green = hexdec(substr($color, 3, 2));
            $blue = hexdec(substr($color, 5, 2));

            return "rgba({$red}, {$green}, {$blue}, 0.2)";
        }

        return $color;
    }
}
