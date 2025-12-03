<div class="min-h-screen mx-auto flex flex-col justify-center items-center overflow-hidden rounded-md bg-light">
    <div class="my-auto px-24 py-16 flex flex-col justify-start items-start gap-10 bg-white rounded-md overflow-hidden">
        <!-- Logo -->
        <div class="flex w-full justify-start items-center">
            {{ $logo }}
        </div>
        <div class="self-stretch flex flex-col justify-start items-start leading-9">
            {{$message}}
        </div>
        <!-- Content -->
        {{ $slot }}
    </div>
</div>
