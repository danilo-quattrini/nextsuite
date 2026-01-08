@props([
    'name' => 'user',
    'variant' => 'outline', // outline | solid | mini | micro
    'size' => 'md',
])

@php
    // Size mapping
    $sizes = [
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
        'xl' => 'w-7 h-7',
        'xxl' => 'w-8 h-8',
    ];

    // Heroicon prefixes based on variant
    $prefix = match($variant) {
        'solid' => 'heroicon-s',
        'mini'  => 'heroicon-m',
        'outline' => 'heroicon-o',
    };

    // Build final component tag class
    $classes = $sizes[$size] . ' ' . ($attributes->get('class'));
@endphp

{{-- Render the correct heroicon dynamically --}}
<x-dynamic-component
        :component="''.$prefix.'-'.$name.''"
        {{ $attributes->merge(['class' => $classes]) }}
/>