@props([
    'level' => 0,
    'showLabel' => false,
    'size' => 'default'
])

@php
    $proficiency = match(true) {
        $level >= 70 => ['label' => 'Expert', 'variant' => 'success'],
        $level >= 50 => ['label' => 'Advanced', 'variant' => 'info'],
        $level >= 40 => ['label' => 'Intermediate', 'variant' => 'warning'],
        $level >= 20 => ['label' => 'Beginner', 'variant' => 'neutral'],
        default => ['label' => 'Novice', 'variant' => 'neutral'],
    };
@endphp

<div class="proficiency-badge proficiency-badge--{{ $size }}">
    <x-tag :variant="$proficiency['variant']" size="sm">
        <span class="proficiency-badge__score">{{ $level }}</span>
        @if($showLabel)
            <span class="proficiency-badge__divider">•</span>
            <span class="proficiency-badge__label">{{ $proficiency['label'] }}</span>
        @endif
    </x-tag>
</div>