@props([
    'label' => null,
    'step' => 1,
    'level' => 0,
    'min' => 0,
    'max' => 100,
    'markers' => null,
])
@php
    $wireModel = $attributes->wire('model');
    $minValue = is_numeric($min) ? (float) $min : 0;
    $maxValue = is_numeric($max) ? (float) $max : 100;
    if ($maxValue < $minValue) {
        $swap = $maxValue;
        $maxValue = $minValue;
        $minValue = $swap;
    }

    $markersList = [];
    $formatMarkerLabel = function (float $value) use ($step): string {
        $stepString = (string) $step;
        $decimals = 0;
        if (str_contains($stepString, '.')) {
            $decimals = strlen(rtrim(explode('.', $stepString)[1], '0'));
        }
        $rounded = round($value, max($decimals, 0));
        $label = (string) $rounded;
        if (str_contains($label, '.')) {
            $label = rtrim(rtrim($label, '0'), '.');
        }
        return $label;
    };

    if (is_array($markers) && count($markers) > 0) {
        foreach ($markers as $marker) {
            $value = null;
            $label = null;

            if (is_array($marker)) {
                $value = array_key_exists('value', $marker) ? $marker['value'] : null;
                $label = array_key_exists('label', $marker) ? $marker['label'] : null;
            } elseif (is_numeric($marker)) {
                $value = $marker;
                $label = (string) $marker;
            } else {
                $label = (string) $marker;
            }

            $markersList[] = [
                'value' => is_numeric($value) ? (float) $value : null,
                'label' => $label,
            ];
        }

        $count = count($markersList);
        $stepCount = max($count - 1, 1);
        foreach ($markersList as $index => $marker) {
            if ($marker['value'] === null) {
                $marker['value'] = $minValue + (($maxValue - $minValue) * ($index / $stepCount));
            }
            if ($marker['label'] === null || $marker['label'] === '') {
                $marker['label'] = $formatMarkerLabel($marker['value']);
            }
            $markersList[$index] = $marker;
        }
    } else {
        $defaultCount = 5;
        $stepCount = max($defaultCount - 1, 1);
        for ($i = 0; $i <= $stepCount; $i++) {
            $value = $minValue + (($maxValue - $minValue) * ($i / $stepCount));
            $markersList[] = [
                'value' => $value,
                'label' => $formatMarkerLabel($value),
            ];
        }
    }

    $range = max($maxValue - $minValue, 1);
    foreach ($markersList as $index => $marker) {
        $clampedValue = min(max($marker['value'], $minValue), $maxValue);
        $markersList[$index]['left'] = (($clampedValue - $minValue) / $range) * 100;
    }
@endphp
<div
        class="range-slider-container"
        @if($wireModel)
            x-data="{ level: @entangle($wireModel), min: {{ $minValue }}, max: {{ $maxValue }} }"
        @else
            x-data="{ level: @js($level), min: {{ $minValue }}, max: {{ $maxValue }} }"
        @endif
>
    @if($label)
        <label class="range-slider-label">{{ $label }}</label>
    @endif

    <div class="range-slider-wrapper">
        <span
                class="range-slider-tooltip"
                x-text="level ?? 0"
                :style="`left: calc(${(Math.min(Math.max(level ?? 0, min), max) - min) / ((max - min) || 1)} * (100% - var(--range-thumb-size)) + (var(--range-thumb-size) / 2));`"
        >
        </span>

        <input
                type="range"
                class="range-slider-input"
                min="{{ $minValue }}"
                max="{{ $maxValue }}"
                value="{{ $level }}"
                x-model="level"
                step="{{ $step }}"
                {{ $attributes }}
        />
    </div>
    @if(count($markersList) > 0)
        <div class="range-slider-markers" aria-hidden="true">
            @foreach($markersList as $marker)
                <div class="range-slider-marker" style="left: {{ $marker['left'] }}%;">
                    <span class="range-slider-marker-line"></span>
                    <span class="range-slider-marker-label">{{ $marker['label'] }}</span>
                </div>
            @endforeach
        </div>
    @endif
        {{ $error ?? null }}
</div>
