@props([
    'size' => 'md',
    'label' => 'Loading',
])

@php
    $sizeClass = match ($size) {
        'sm' => 'spinner--sm',
        'lg' => 'spinner--lg',
        default => 'spinner--md',
    };
@endphp

<div {{ $attributes->merge(['class' => "spinner {$sizeClass}"]) }} role="status" aria-live="polite">
    <span class="spinner__ring" aria-hidden="true"></span>
    <span class="sr-only">{{ $label }}</span>
</div>
