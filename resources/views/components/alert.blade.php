@props([
    'type' => 'success',
    'title' => null,
    'message' => null,
])

@php
    $colors = [
        'success' => [
            'icon-bg' => 'bg-secondary-success',
            'text' => 'text-secondary-success'
        ],
        'warning' => [
            'icon-bg' => 'bg-secondary-warning',
            'text' => 'text-secondary-warning'
        ],
        'info' => [
            'icon-bg' => 'bg-primary',
            'text' => 'text-primary'
        ],
        'error' => [
            'icon-bg' => 'bg-secondary-error',
            'text' => 'text-secondary-error'
        ]
    ];
    $icons = [
        'success' => 'check',
        'warning' => 'exclamation-triangle',
        'error' => 'x-circle',
        'info' => 'information-circle'
    ];


    $style = $colors[$type];
    $icon = $icons[$type];
@endphp

<div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="flex items-start z-50 w-full  p-6 rounded-md bg-white  border-outline-grey shadow-lg gap-4 "
>

    {{-- ICON BOX --}}
    <div class="mt-0.5 w-6 h-6 flex items-center justify-center rounded-md {{ $style['icon-bg'] }}">
        <x-heroicon
                size="sm"
                :name="$icon"
                variant="solid"
                class="text-white"
        />
    </div>

    {{-- CONTENT --}}
    <div class="flex flex-col gap-0.5">
        <span class="self-stretch text-base font-semibold {{ $style['text'] }}">
            {{ $title }}
        </span>

        <div class="self-stretch text-sm leading-5">
            {{ $message }}
        </div>
    </div>

</div>
