@props([
    'type' => 'success',
    'title' => null,
    'message' => null,
])

@php
    $colors = [
        'success' => [
            'icon-bg' => 'bg-secondary-success',
            'bg' => 'bg-secondary-success-100',
            'border' => 'border-secondary-success',
            'text' => 'text-secondary-success'
        ],
        'warning' => [
            'icon-bg' => 'bg-secondary-warning',
            'bg' => 'bg-secondary-warning-100',
            'border' => 'border-secondary-warning',
            'text' => 'text-secondary-warning'
        ],
        'error' => [
            'icon-bg' => 'bg-secondary-error',
            'bg' => 'bg-secondary-error-100',
            'border' => 'border-secondary-error',
            'text' => 'text-secondary-error'
        ]
    ];
    $icons = [
        'success' => 'check',
        'warning' => 'exclamation-triangle',
        'error' => 'x-circle',
    ];


    $style = $colors[$type];
    $icon = $icons[$type];
@endphp

<div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition:enter="transform transition ease-out duration-500"
        x-transition:enter-start="translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transform transition ease-in duration-500"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-full opacity-0"
        class="w-full px-6 py-6 rounded-md border {{ $style['bg'] }} {{ $style['border'] }} flex gap-4 items-start"
>

    {{-- ICON BOX --}}
    <div class="w-9 h-9 flex items-center justify-center rounded-md {{ $style['icon-bg'] }}">
        <x-heroicon
                size="md"
                :name="$icon"
                variant="solid"
                class="text-white"
        />
    </div>

    {{-- CONTENT --}}
    <div class="flex flex-col gap-2">
        <div class="self-stretch text-2xl font-bold {{ $style['text'] }}">
            {{ $title }}
        </div>

        <div class="self-stretch text-primary-grey text-lg font-medium leading-6">
            {{ $message }}
        </div>
    </div>

</div>
