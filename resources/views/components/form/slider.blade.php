@props([
    'label' => null,
    'step' => 1,
    'level' => 0,
])
@php
    $wireModel = $attributes->wire('model');
@endphp
<div
        class="range-slider-container"
        @if($wireModel)
            x-data="{ level: @entangle($wireModel), min: 0, max: 100 }"
        @else
            x-data="{ level: @js($level), min: 0, max: 100 }"
        @endif
>
    @if($label)
        <label class="range-slider-label">{{ $label }}</label>
    @endif

    <div class="range-slider-wrapper">
        <span
                class="range-slider-tooltip"
                x-text="level ?? 0"
                :style="`left: ${((level ?? 0) - min) / (max - min) * 100}%;`"
        >
        </span>

        <input
                type="range"
                class="range-slider-input"
                min="0"
                max="100"
                value="{{ $level }}"
                x-model="level"
                step="{{ $step }}"
                {{ $attributes }}
        />
    </div>
        {{ $error ?? null }}
</div>
