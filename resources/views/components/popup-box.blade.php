@props([
    'modal' => null
])
<div class="popup-overlay">
    <div class="popup-card"
         @click.outside="$wire.set('{{ $modal }}', false)"
    >
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