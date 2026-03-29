<x-guest-layout>
    <x-authentication-card>
        <x-slot:logo>
            <x-authentication-card-logo />
        </x-slot:logo>
        <x-slot:message>
            <h4 class="mb-2">{{ __('Welcome back') }}!<br>{{ __('Sign in to get started') }}</h4>
            <p class="text-primary-grey font-normal text-sm">{{ __('Enter your details below') }}</p>
        </x-slot:message>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <x-form.container>
                <!-- EMAIL -->
                <x-form.input-container>
                    <x-form.label-container label="{{ __('Email') }}"/>
                    <x-input id="email" name="email" :value="old('email')" autofocus autocomplete="email" type="email" right-icon="envelope" placeholder="you@example.com" :error="$errors->has('email')" />
                    <x-input-error for="email"/>
                </x-form.input-container>

                <!-- PASSWORD -->
                <x-form.input-container>
                    <x-form.label-container label="{{ __('Password') }}">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">
                                <x-span-link>{{ __('Forgot password?') }}</x-span-link>
                            </a>
                        @endif
                    </x-form.label-container>
                    <x-input id="password" name="password" type="password" right-icon="eye" placeholder="••••••••" :error="$errors->has('password')" />
                    <x-input-error for="password"/>
                </x-form.input-container>
            </x-form.container>

            <!-- REMEMBER ME & REGISTER LINK -->
            <div class="mt-6 mb-8 flex items-center justify-between w-full">
                <x-toggle-container>
                    <x-slot:element>
                        <x-form.checkbox id="remember_me" name="remember" size="sm" />
                    </x-slot:element>
                    <x-slot:span>
                        <span class="ds-checkbox-mark"></span>
                    </x-slot:span>
                    {{ __('Remember me') }}
                </x-toggle-container>

                <a href="{{ route('register') }}">
                    <x-span-link>{{ __("Don't have an account?") }}</x-span-link>
                </a>
            </div>

            <!-- SUBMIT -->
            <div class="w-full flex items-center justify-center mb-8">
                <x-button size="full" type="submit">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>