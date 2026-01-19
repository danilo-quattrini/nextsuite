<x-app-layout>
    <div class="flex w-full mx-auto justify-center items-center">

        {{--  Content  --}}
        <div class="flex-col space-y-6">

            {{--  Page Header  --}}
            <h1 class="text-2xl font-bold text-black">
                {{ __('New Template') }}
            </h1>

            <p class="text-sm text-primary-grey">
                {{__('Create a new Template!')}}
            </p>

            {{--  Form  --}}
            <x-form.container>
                @livewire('template.create-template')
            </x-form.container>
        </div>

    </div>
</x-app-layout>
