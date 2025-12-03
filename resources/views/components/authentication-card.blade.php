<div class="min-h-screen mx-auto flex flex-col justify-center items-center gap-2.5 overflow-hidden rounded-md bg-light">
    <div class="my-auto px-24 py-16 flex flex-col justify-start items-start gap-10 bg-white rounded-md overflow-hidden">
        <!-- Logo -->
        <div class="flex justify-center items-center">
            {{ $logo }}
        </div>
        <!-- Content -->
        {{ $slot }}
    </div>
</div>
