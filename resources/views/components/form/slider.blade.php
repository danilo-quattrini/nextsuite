@props([
    'label' => null,
    'step' => 1
    ])
<div
        class="range-slider-container"
        x-data="{ level: 0, min: 0, max: 100 }"
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
                value="0"
                x-model="level"
                step="{{ $step }}"
                {{ $attributes }}
        />
    </div>
        {{ $error ?? null }}
</div>