<x-guest-layout>
    <x-authentication-card>
        <x-slot:logo>
            <x-authentication-card-logo />
        </x-slot:logo>
        <x-slot:message>
            <h4 class="mb-sm">{{__('Warning')}}</h4>
            <p class="text-primary-grey ">{{__('This is an important choice that can change the flow of events.')}}</p>
        </x-slot:message>

        {{--ICON--}}
        <div class="flex self-stretch justify-center items-center mb-sm ">
            <div class="w-24 h-24 rounded-full bg-secondary-warning-100 flex items-center justify-center">
                <x-heroicon
                        name="exclamation-triangle"
                        class="text-secondary-warning"
                        size="2xl"
                />
            </div>
        </div>
        {{--IMPORTANT MESSAGE--}}
        <div class="flex self-stretch justify-start">
            <h5>{{__('Do you want to create a company now?')}}</h5>
        </div>

        {{-- BUTTON CHOICES --}}
        <div class="flex self-stretch justify-center gap-sm">
            <x-button
                    size="full"
                    variant="rest"
                    href="{{ route('dashboard') }}"
            >
                {{__('Skip')}}
            </x-button>

            <x-button
                    size="full"
                    href="{{ route('auth.company.create') }}"
            >
                {{__('Create company')}}
            </x-button>
        </div>
    </x-authentication-card>
</x-guest-layout>
