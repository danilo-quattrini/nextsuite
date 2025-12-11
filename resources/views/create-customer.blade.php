<x-app-layout>
    <div class="flex w-full mx-auto justify-center items-center">
        <div class="flex-col space-y-6">
            <h1>New Customer</h1>
            <x-form.container>
                @livewire('create-customer')
            </x-form.container>
        </div>
    </div>
</x-app-layout>
