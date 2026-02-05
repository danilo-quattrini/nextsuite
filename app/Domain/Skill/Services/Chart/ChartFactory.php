<?php

namespace App\Domain\Skill\Services\Chart;

use InvalidArgumentException;

class ChartFactory
{
    public static function make(string $type): Charts
    {
        $normalizedType = strtolower($type);

        return match ($normalizedType) {
            'bar' => new BarChart(),
            'line' => new LineChart(),
            'pie' => new PieChart(),
            'doughnut' => new DoughnutChart(),
            'radar' => new RadarChart(),
            default => throw new InvalidArgumentException(
                'Unsupported chart [' . $type . ']. Supported charts: ' . implode(', ', self::supportedTypes())
            ),
        };
    }

    public static function supportedTypes(): array
    {
        return ['bar', 'line', 'pie', 'doughnut', 'radar'];
    }
}
