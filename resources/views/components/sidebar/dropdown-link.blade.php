@props(['active' => false])

@php
    $class = 'sidebar__dropdown-toggle';

    if ($active) {
        $class .= ' sidebar__link--active';
    }
@endphp

<div
    x-data="{ open: {{ $active ? 'true' : 'false'}} }"
    x-effect="if (collapsed) open = false"
    @click.outside="open = false"
>
    <button
            type="button"
            @click="if (!collapsed) open = !open"
            x-bind:aria-expanded="open"
            {{ $attributes->merge(['class' => $class]) }}
    >
        <span class="sidebar__link-icon">
            {{ $icon }}
        </span>
        <span class="sidebar__link-label">
            {{ $slot }}
        </span>

        <x-heroicon
                name="chevron-down"
                size="md"
                class="sidebar__dropdown-caret"
                x-bind:class="open ? 'rotate-180' : ''"
                x-show="!collapsed"
        />
    </button>
    <div
            x-cloak
            x-show="open && !collapsed"
            x-collapse.duration.200ms
            @click.stop
            class="sidebar__dropdown-items"
    >
        {{ $elements }}
    </div>
</div>
