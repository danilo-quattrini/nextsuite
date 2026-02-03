<x-app-layout>
    <div class="flex w-full mx-auto justify-center items-center">

        {{--  Content  --}}
        <div class="flex-col space-y-6">

            {{--  Page Header  --}}
            <h1>
                {{ __('Create Customer') }}
            </h1>

            <p class="text-sm text-primary-grey">
                {{__('Complete the details in two steps.')}}
            </p>

            {{--  Form  --}}
            <x-form.container>
                @livewire('customer.create-customer')
            </x-form.container>
        </div>

    </div>
</x-app-layout>
