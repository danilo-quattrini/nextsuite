<x-app-layout>
    <div class="container mx-auto px-4 sm:px-8">

        {{--  Content  --}}
        <div class="flex-col space-y-6">

            {{--  Page Header  --}}
            <h1 class="text-2xl font-bold text-black">
                {{ __('Style your template!') }}
            </h1>

            <p class="text-sm text-primary-grey">
                {{__('Drag and drop the sections you want to place inside the template!')}}
            </p>

            {{--  Section / Canva  --}}
            <x-form.container>
                <livewire:template.layout-editor :template="$template"/>
            </x-form.container>
        </div>

    </div>
</x-app-layout>

