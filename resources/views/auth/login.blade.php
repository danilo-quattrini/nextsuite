<x-guest-layout>
    <x-authentication-card>
        <x-slot:logo>
            <x-authentication-card-logo />
        </x-slot:logo>
        <x-slot:message>
            <h3 class="mb-4">{{__('Welcome back')}}!<br> {{__('Sign In to getting start')}}</h3>
            <p class="text-primary-grey text-base font-medium">Enter your details to start</p>
        </x-slot:message>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <x-form.container>
                <!-- EMAIL -->
                <x-form.input-container>
                    <x-form.label-container label="{{ __('Email') }}"/>
                    <x-input id="email" name="email" :value="old('email')" autofocus autocomplete="email" type="email" right-icon="envelope" placeholder="Email" :error="$errors->has('email')"></x-input>
                    <x-input-error for="email"/>
                </x-form.input-container>

                <!-- PASSWORD -->
                <x-form.input-container>
                    <x-form.label-container label="Password">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">
                                <x-span-link> {{ __('Forgot your password?') }} </x-span-link>
                            </a>
                        @endif
                    </x-form.label-container>
                    <x-input id="password" name="password" type="password" right-icon="eye" placeholder="Password" :error="$errors->has('password')"></x-input>
                    <x-input-error for="password"/>
                </x-form.input-container>
            </x-form.container>
            <!-- TOGGLE &  LINK -->
            <div class="my-10 flex flex-1 justify-between w-full">
                <x-toggle-container>
                    <x-slot:element>
                        <x-form.checkbox
                                id="remember_me"
                                name="remember"
                                size="sm"
                        />
                    </x-slot:element>

                    <x-slot:span>
                        <span class="ds-checkbox-mark"></span>
                    </x-slot:span>
                    {{ __('Remember Me') }}
                </x-toggle-container>
                <a href="/register">
                    <x-span-link>{{ __('Don\'t have an account?') }}</x-span-link>
                </a>
            </div>

            <!-- LOGIN BUTTON -->
            <div class="w-[470px] flex items-center justify-center mb-10">
                <x-button size="large" type="submit">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
