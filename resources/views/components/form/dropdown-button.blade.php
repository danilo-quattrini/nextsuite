@props([
    'align' => 'left',
    'width' => 'w-72'
])

<div
        x-data="{ open: false }"
        @click.outside="open = false"
        class="relative"
>
    {{-- Trigger --}}
    <div @click="open = !open">
        {{ $trigger }}
    </div>

    {{-- Dropdown --}}
    <div
            x-show="open"
            x-transition
            class="absolute z-50 mt-2 {{ $width }} rounded-md border border-outline-grey bg-white shadow-md p-4"
            :class="{
            'left-0': '{{ $align }}' === 'left',
            'right-0': '{{ $align }}' === 'right'
        }"
    >
        {{ $content }}
    </div>
</div>
