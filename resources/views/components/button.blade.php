@props([
    'variant' => 'primary',
    'type' => 'button',
    'href' => null,
])

@php
    $base = 'btn';

    // Style variants
    $variants = [
        'primary' => 'btn-primary',
        'rest' => 'btn-rest',
        'disable' => 'btn-disable',
        'error' => 'btn-error',
        'warning' => 'btn-warning',
        'outline-primary' => ' btn-outline-primary',
        'outline-disable' => ' btn-outline-disable',
        'outline-error' => ' btn-outline-error',
        'outline-warning' => ' btn-outline-warning',
        ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif