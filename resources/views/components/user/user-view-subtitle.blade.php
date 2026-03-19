@props([
    'icon' => 'information-circle',
    'size' => 'md',
    'color' => 'muted',
    'text' => null,
])

@php
    $sizeClass = match ($size) {
        'sm' => 'user-view__subtitle--sm',
        'lg' => 'user-view__subtitle--lg',
        default => 'user-view__subtitle--md',
    };

    $colorClass = match ($color) {
        'primary' => 'user-view__subtitle--primary',
        'success' => 'user-view__subtitle--success',
        'warning' => 'user-view__subtitle--warning',
        'danger' => 'user-view__subtitle--danger',
        default => 'user-view__subtitle--muted',
    };
@endphp

<div {{ $attributes->merge(['class' => "user-view__subtitle {$sizeClass} {$colorClass}"]) }}>
    <x-heroicon :name="$icon" :size="$size"/>
    <span>
        {{ $text ?? $slot }}
    </span>
</div>
