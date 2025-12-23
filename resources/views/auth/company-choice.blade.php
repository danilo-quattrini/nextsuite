<x-guest-layout>
    <x-authentication-card>
        <x-slot:logo>
            <x-authentication-card-logo />
        </x-slot:logo>
        <x-slot:message>
            <h3 class="mb-4">{{__('Warning')}}</h3>
            <p class="text-primary-grey text-base font-medium">{{__('This is an important choice that can change the flow of events.')}}</p>
        </x-slot:message>

        {{--IMPORTANT MESSAGE--}}
        <div class="flex self-stretch justify-center">
            <h4>{{__('Do you want to create a company now?')}}</h4>
        </div>

        {{-- BUTTON CHOICES --}}
        <div class="flex self-stretch justify-between ">
            <x-button size="large" variant="rest" href="{{ route('dashboard') }}">
                {{__('Skip for now')}}
            </x-button>

            <x-button size="large" href="{{ route('company.create') }}">
                {{__('Create a company')}}
            </x-button>
        </div>
    </x-authentication-card>
</x-guest-layout>
