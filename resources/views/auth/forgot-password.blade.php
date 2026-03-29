<x-guest-layout>
    <x-authentication-card>
        <x-slot:logo>
            <x-authentication-card-logo />
        </x-slot:logo>
        <x-slot:message>
            <h4 class="mb-2">{{__('Lost your password?')}}</h4>
            <p class="text-primary-grey font-normal text-sm">{{__('Forgot your password? No problem!')}}</p>
        </x-slot:message>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <x-form.container>
                <!-- EMAIL -->
                <x-form.input-container>
                    <x-form.label-container label="{{ __('Email') }}"/>
                    <x-input id="email" name="email" :value="old('email')" required autofocus autocomplete="username" type="email" right-icon="envelope" placeholder="you@example.com" :error="$errors->has('email')"></x-input>
                    <x-input-error for="email"/>
                </x-form.input-container>

                <!-- RECOVE BUTTON -->
                <div class="w-full flex items-center justify-center mb-10">
                    <x-button size="full" type="submit">
                        {{ __('Recover') }}
                    </x-button>
                </div>
            </x-form.container>
        </form>
    </x-authentication-card>
</x-guest-layout>
