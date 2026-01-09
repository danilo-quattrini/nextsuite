@props([
    'variant' => 'primary',
    'size' => 'default',
    'type' => 'tag',
    'href' => null,
])

@php
    $base = 'tag';

    $variants = [
        'primary' => 'tag-primary',
        'white' => 'tag-white',
    ];

    $sizes = [
        'default' => 'h-[38px] px-3',
        'large'   => 'h-[38px] px-4',
        'auto'    => 'h-[38px] px-4',
        'full'    => 'w-full h-[38px] px-4',
    ];

    $classes = trim(implode(' ', [
        $base,
        $variants[$variant] ?? $variants['primary'],
        $sizes[$size] ?? $sizes['default'],
        'inline-flex items-center gap-2 leading-none',
    ]));
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @isset($leading)
        <span class="shrink-0">
            {{ $leading }}
        </span>
    @endisset

    <span class="truncate">
        {{ $slot }}
    </span>

    @isset($trailing)
        <span class="shrink-0">
            {{ $trailing }}
        </span>
    @endisset
</span>