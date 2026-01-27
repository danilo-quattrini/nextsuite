@props([
    'variant' => 'primary',
    'size' => 'tag-size-default',
    'type' => 'tag',
    'href' => null,
])

@php
    $base = 'tag';

    $variants = [
        'primary' => 'tag-primary',
        'green' => 'tag-success',
        'yellow' => 'tag-warning',
        'red' => 'tag-error',
        'white' => 'tag-white',
    ];

    $sizes = [
        'default' => 'tag-size-default',
        'large'   => 'tag-size-large',
        'auto'    => 'tag-size-auto',
        'full'    => 'tag-size-full',
    ];

    $classes = trim(implode(' ', [
        $base,
        $variants[$variant] ?? $variants['primary'],
        $sizes[$size] ?? $sizes['default'],
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
