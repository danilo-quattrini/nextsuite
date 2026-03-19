@props([
    'modal' => null,
    'transition' => true
])
<div class="popup"
     wire:click.self="$dispatch('close-modal')"
>
    <div class="popup__card">
        <div class=" text-black">
            {{ $header }}
        </div>

        <div class=" text-black font-bold text-xl">
            {{ $subheader }}
        </div>

        <div class=" text-primary-grey font-medium text-sm">
            {{ $message }}
        </div>
        <div>
            {{ $slot }}
        </div>
    </div>
</div>
