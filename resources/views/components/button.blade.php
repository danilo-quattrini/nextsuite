@props([
    'variant' => 'primary',
    'size' => 'default',
    'type' => 'button',
    'href' => null,
])

@php
    $base = 'btn';

    $variants = [
        'primary' => 'btn-primary',
        'white' => 'btn-white',
        'rest' => 'btn-rest',
        'disable' => 'btn-disable',
        'error' => 'btn-error',
        'warning' => 'btn-warning',
        'outline-primary' => 'btn-outline-primary',
        'outline-disable' => 'btn-outline-disable',
        'outline-error' => 'btn-outline-error',
        'outline-warning' => 'btn-outline-warning',
        'outline-dashed' => 'btn-outline-disable-dashed'
    ];

    $sizes = [
        'default' => 'w-[72px] h-[38px]',
        'large' => 'w-[150px] h-[38px]',
        'auto' => 'px-4 py-2 h-[38px]',
        'full' => 'w-full h-[38px]'
    ];

    $classes = trim(implode(' ', [
        $base,
        $variants[$variant] ?? $variants['primary'],
        $sizes[$size] ?? $sizes['default'],
    ]));
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