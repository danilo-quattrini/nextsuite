@props([
    'name' => 'user',
    'variant' => 'outline', // outline | solid | mini | micro
    'size' => 'md',
])

@php
    // Size mapping
    $sizes = [
        'sm' => 'w-3 h-3',
        'md' => 'w-4 h-4',
        'lg' => 'w-5 h-5',
        'xl' => 'w-6 h-6',
        '2xl' => 'w-7 h-7',
        '3xl' => 'w-8 h-8',
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