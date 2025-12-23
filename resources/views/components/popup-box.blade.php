@props([
    'modal' => null
])
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="flex flex-col justify-center items-center gap-6 bg-white rounded-md px-10 py-12 w-full max-w-fit"
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