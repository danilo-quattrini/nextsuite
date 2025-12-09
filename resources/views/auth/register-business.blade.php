<x-guest-layout>
    <x-authentication-card>
        <x-slot:logo>
            <x-authentication-card-logo />
        </x-slot:logo>
        <x-slot:message>
            <h3 class="mb-4">{{__('Register your business')}}</h3>
            <p class="text-primary-grey text-base font-medium">{{__('Enter your details to start')}}</p>
        </x-slot:message>
        <livewire:create-company/>
    </x-authentication-card>
</x-guest-layout>
