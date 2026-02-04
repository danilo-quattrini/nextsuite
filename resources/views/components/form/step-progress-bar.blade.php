@props([
    'steps' => [],
    'current' => 1,
    'wrapperClass' => 'flex items-center gap-4',
    'stepWrapperClass' => 'flex items-center gap-3',
    'connectorClass' => 'h-px flex-1 bg-outline-grey',
    'numberBaseClass' => 'flex h-8 w-8 items-center justify-center rounded-md text-sm font-medium',
    'activeNumberClass' => 'bg-primary text-white',
    'inactiveNumberClass' => 'bg-white border border-outline-grey',
    'labelBaseClass' => 'text-sm font-medium',
])

@php
    $steps = $steps ?? [];
    $totalSteps = count($steps);
@endphp

<div {{ $attributes->merge(['class' => $wrapperClass]) }}>
    @foreach($steps as $index => $step)
        @php
            $stepKey = is_array($step) ? ($step['key'] ?? ($step['id'] ?? $index + 1)) : ($index + 1);
            $label = is_array($step) ? ($step['label'] ?? ($step['name'] ?? $stepKey)) : $step;
            $number = is_array($step) ? ($step['number'] ?? ($index + 1)) : ($index + 1);
            $stepActive = is_array($step) && array_key_exists('active', $step)
                ? (bool) $step['active']
                : ((string) $current === (string) $stepKey);
            $stepNumberClass = is_array($step) ? ($step['numberClass'] ?? '') : '';
            $stepLabelClass = is_array($step) ? ($step['labelClass'] ?? '') : '';
            $stepActiveNumberClass = is_array($step) ? ($step['activeNumberClass'] ?? '') : '';
            $stepInactiveNumberClass = is_array($step) ? ($step['inactiveNumberClass'] ?? '') : '';
            $stepWrapper = is_array($step) ? ($step['stepClass'] ?? '') : '';
        @endphp

        <div class="{{ $stepWrapperClass }} {{ $stepWrapper }}">
            <span class="{{ $numberBaseClass }} {{ $stepNumberClass }} {{ $stepActive ? ($activeNumberClass . ' ' . $stepActiveNumberClass) : ($inactiveNumberClass . ' ' . $stepInactiveNumberClass) }}">
                {{ $number }}
            </span>
            <span class="{{ $labelBaseClass }} {{ $stepLabelClass }}">
                {{ $label }}
            </span>
        </div>

        @if ($index < $totalSteps - 1)
            <div class="{{ $connectorClass }}"></div>
        @endif
    @endforeach
</div>
