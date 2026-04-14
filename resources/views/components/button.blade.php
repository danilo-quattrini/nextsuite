@props([
    'variant' => 'primary',
    'size' => 'md',
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
        'sm'   => 'btn-sm',
        'md'   => 'btn-md',
        'lg'   => 'btn-lg',
        'icon' => 'btn-icon',
        'default' => 'w-[72px] h-[40px]', /* TO REPLACE IT WITH MD*/
        'large' => 'w-[150px] h-[40px]', /* TO REPLACE IT WITH LG*/
        'auto' => 'px-4 py-2 h-[40px]', /* TO  REPLACE IT WITH FULL*/
        'full' => 'btn-full'
    ];

    $classes = trim(implode(' ', [
        $base,
        $variants[$variant] ?? $variants['primary'],
        $sizes[$size] ?? $sizes['default'],
        $loading  ? 'btn-loading'  : '',
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
       {{ $slot }}
    </a>
@else
    <button
            type="{{ $type }}"
            {{ $isDisabled ? 'disabled' : '' }}
            {{ $attributes->merge(['class' => $classes]) }}
    >
        {{ $slot }}
    </button>
@endif