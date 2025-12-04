<x-guest-layout>
    <x-authentication-card>
        <x-slot:logo>
            <x-authentication-card-logo />
        </x-slot:logo>
        <x-slot:message>
            <h3 class="mb-4">{{__('Lost your password?')}}</h3>
            <p class="text-primary-grey text-base font-medium">{{__('Forgot your password? No problem!')}}</p>
        </x-slot:message>

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <x-form.container>
                <!-- EMAIL -->
                <x-form.input-container>
                    <x-form.label-container label="{{ __('Email') }}"/>
                    <x-input id="email" name="email" :value="old('email')" required autofocus autocomplete="username" type="email" right-icon="envelope" placeholder="Email"></x-input>
                    <x-input-error for="email"/>
                </x-form.input-container>

                <!-- RECOVE BUTTON -->
                <div class="w-[470px] flex items-center justify-center mb-10">
                    <x-button size="large" type="submit">
                        {{ __('Recover') }}
                    </x-button>
                </div>
            </x-form.container>
        </form>
    </x-authentication-card>
</x-guest-layout>
