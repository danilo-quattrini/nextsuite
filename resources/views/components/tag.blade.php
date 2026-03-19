@props([
    'variant' => 'primary',
    'size' => 'md',
    'outlined' => false,
    'rounded' => 'default',
    'clickable' => false,
    'dismissible' => false,
    'href' => null,
    'icon' => null,
    'iconPosition' => 'leading', // 'leading' or 'trailing'
])

@php
    $base = 'tag';

    $variants = [
        'primary' => 'tag-primary',
        'secondary' => 'tag-secondary',
        'success' => 'tag-success',
        'warning' => 'tag-warning',
        'error' => 'tag-error',
        'info' => 'tag-info',
        'neutral' => 'tag-neutral',
        'white' => 'tag-white',
        'dark' => 'tag-dark',
    ];

    $sizes = [
        'xs' => 'tag-size-xs',
        'sm' => 'tag-size-sm',
        'md' => 'tag-size-md',
        'lg' => 'tag-size-lg',
        'xl' => 'tag-size-xl',
    ];

    $roundedOptions = [
        'none' => 'tag-rounded-none',
        'sm' => 'tag-rounded-sm',
        'default' => 'tag-rounded-default',
        'lg' => 'tag-rounded-lg',
        'full' => 'tag-rounded-full',
    ];

    $classes = collect([
        $base,
        $variants[$variant] ?? $variants['primary'],
        $sizes[$size] ?? $sizes['default'],
        $roundedOptions[$rounded] ?? $roundedOptions['default'],
        $outlined ? 'tag-outlined' : '',
        $clickable || $href ? 'tag-clickable' : '',
        $dismissible ? 'tag-dismissible' : '',
    ])->filter()->implode(' ');

    $component = $href ? 'a' : 'span';
@endphp

<{{ $component }}
{{ $attributes->merge([
    'class' => $classes,
    'href' => $href,
    'role' => $clickable ? 'button' : null,
    'tabindex' => $clickable ? '0' : null,
]) }}
>
{{-- Leading Content --}}
@if($icon && $iconPosition === 'leading')
    <span class="tag__icon tag__icon--leading">
            <x-heroicon :name="$icon" class="tag__icon-svg" />
    </span>
@endif

@isset($leading)
    <span class="tag__leading">
            {{ $leading }}
    </span>
@endisset

{{-- Main Content --}}
    <span class="tag__content">
        {{ $slot }}
    </span>

{{-- Trailing Content --}}
@isset($trailing)
    <span class="tag__trailing">
            {{ $trailing }}
    </span>
@endisset

@if($icon && $iconPosition === 'trailing')
    <span class="tag__icon tag__icon--trailing">
            <x-dynamic-component :component="$icon" class="tag__icon-svg" />
    </span>
@endif

{{-- Dismiss Button --}}
@if($dismissible)
    <button
            type="button"
            class="tag__dismiss"
            wire:click="$dispatch('tag-dismissed', { value: '{{ $slot }}' })"
            aria-label="Dismiss"
    >
        <x-heroicon
                name="x-mark"
                variant="solid"
                size="{{ $size }}"
        />
    </button>
@endif
</{{ $component }}>