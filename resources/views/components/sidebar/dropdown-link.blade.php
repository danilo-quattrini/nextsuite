@php
    $class = ' w-full flex justify-between items-center gap-2 px-4 py-2 rounded-md cursor-pointer';
    $text  = ' font-medium text-base flex-1 ';
@endphp

@props(['active'])

<div x-data="{ open: {{ $active ? 'true' : 'false'}} }"
     @click.outside="open = false"
>
    <button
            @click="open = !open"
            {{ $attributes->merge(['class' => $class]) }}
    >
        <div class="flex items-center gap-2">
            {{ $icon }}
            <span class="{{ $text }}">{{ $slot }}</span>
        </div>

        <x-heroicon
                name="chevron-down"
                size="md"
                class="transition-transform"
                x-bind:class="open ? 'rotate-180' : ''"
        />
    </button>
    <div
            x-show="open"
            x-collapse.duration.200ms
            @click.stop
            class="ml-8 mt-2 space-y-1"
    >
        {{ $elements }}
    </div>
</div>