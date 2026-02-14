@props([
    'size' => 'md',
    'label' => null,
    'wrap' => false,
    'containerClass' => '',
    'boxClass' => '',
    'labelClass' => '',
    'inputClass' => '',
])

@php
    $allowedSizes = ['sm', 'md', 'lg'];
    $size = strtolower((string) $size);
    $size = in_array($size, $allowedSizes, true) ? $size : 'md';
    $wrap = filter_var($wrap, FILTER_VALIDATE_BOOL);
    $inputSizeClass = "ds-checkbox-input--{$size}";
    $wrapperSizeClass = "ds-checkbox--{$size}";
    $hasSlot = trim((string) $slot) !== '';
    $labelText = $label ?? ($hasSlot ? $slot : null);
    $inputClasses = trim("ds-checkbox-input {$inputSizeClass} {$inputClass}");
    $wrapperClasses = trim(implode(' ', [
        'ds-checkbox',
        $wrapperSizeClass,
        $containerClass,
        $attributes->get('class'),
    ]));
@endphp

@if($wrap)
    <label class="{{ $wrapperClasses }}">
        <input type="checkbox" {{ $attributes->except('class')->merge(['class' => $inputClasses]) }} />
        <span class="ds-checkbox-mark {{ $boxClass }}"></span>
        @if($labelText)
            <span class="ds-checkbox-label {{ $labelClass }}">{{ $labelText }}</span>
        @endif
    </label>
@else
    <input type="checkbox" {{ $attributes->merge(['class' => $inputClasses]) }} />
@endif
