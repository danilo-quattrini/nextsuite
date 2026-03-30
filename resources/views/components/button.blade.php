@props([
    'variant' => 'primary',
    'size' => 'default',
    'type' => 'button',
    'href' => null,
    'disabled' => false,
    'loading'  => false,
    'external' => false,
])

@php
    $base = 'btn';

    $variants = [
        'primary' => 'btn-primary',
        'white' => 'btn-white',
        'rest' => 'btn-ghost',
        'disable' => 'btn-disable',
        'error' => 'btn-error',
        'warning' => 'btn-warning',
        'outline-primary' => 'btn-outline-primary',
        'outline-muted' => 'btn-outline-muted',
        'outline-error' => 'btn-outline-error',
        'outline-warning' => 'btn-outline-warning',
        'outline-dashed' => 'btn-outline-dashed'
    ];

    $sizes = [
        'default' => 'w-[72px] h-[40px]',
        'large' => 'w-[150px] h-[40px]',
        'auto' => 'px-4 py-2 h-[40px]',
        'full' => 'w-full h-[40px]'
    ];

    $classes = trim(implode(' ', [
        $base,
        $variants[$variant] ?? $variants['primary'],
        $sizes[$size] ?? $sizes['default'],
    ]));

    $isDisabled = $disabled || $loading;
@endphp

@if($href)
    <a
            href="{{ $isDisabled ? '#' : $href }}"
            {{ $external ? 'target=_blank rel=noopener' : '' }}
            aria-disabled="{{ $isDisabled ? 'true' : 'false' }}"
            {{ $attributes->merge(['class' => $classes]) }}
    >
        <span class="btn-label">{{ $slot }}</span>
    </a>
@else
    <button
            type="{{ $type }}"
            {{ $isDisabled ? 'disabled' : '' }}
            {{ $attributes->merge(['class' => $classes]) }}
    >
        <span class="btn-label">{{ $slot }}</span>
    </button>
@endif