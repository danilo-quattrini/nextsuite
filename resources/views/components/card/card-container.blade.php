@props([
    'title' => null,
    'icon' => null,
    'subtitle' => null,
    'size' => '3xl',
    'cardSize' => 'md'
])
@php
    $titleSizes = [
        'sm' => 'card-title--sm',
        'md' => 'card-title--md',
        'lg' => 'card-title--lg',
        'xl' => 'card-title--xl',
        '2xl' => 'card-title--2xl',
        '3xl' => 'card-title--3xl'
    ];

    $containerSizes = [
        'sm' => 'card-container--sm',
        'md' => 'card-container--md',
        'lg' => 'card-container--lg'
    ];
@endphp
<div {{ $attributes->merge(['class' => 'card-container ' . ($containerSizes[$cardSize] ?? $containerSizes['md'])]) }}>
    @if($title || isset($action))
        <div class="card-head">
            <div>
                @if($title)
                    <div class="card-title">
                        @if($icon)
                            <x-heroicon :name="$icon" :size="$size"/>
                        @endif
                        <span class="card-title {{ $titleSizes[$size] ?? $titleSizes['3xl'] }}">{{ $title }}</span>
                    </div>
                @endif
                @if($subtitle)
                    <p class="card-subtitle">{{ $subtitle }}</p>
                @endif
            </div>
            {{ $action ?? null }}
        </div>
    @endif
    {{ $slot }}
</div>
