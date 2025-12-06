<x-guest-layout>
    <x-authentication-card>
        <x-slot:logo>
            <x-authentication-card-logo />
        </x-slot:logo>
        <x-slot:message>
            <h3 class="mb-4">{{__('Welcome back')}}!<br> {{__('Sign In to getting start')}}</h3>
            <p class="text-primary-grey text-base font-medium">Enter your details to start</p>
        </x-slot:message>

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <x-form.container>
                <!-- EMAIL -->
                <x-form.input-container>
                    <x-form.label-container label="{{ __('Email') }}"/>
                    <x-input id="email" name="email" :value="old('email')" required autofocus autocomplete="username" type="email" right-icon="envelope" placeholder="Email" :error="$errors->has('email')"></x-input>
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
                    <x-input id="password" name="password" required autofocus autocomplete="username" type="password" right-icon="eye" placeholder="Password" :error="$errors->has('password')"></x-input>
                    <x-input-error for="password"/>
                </x-form.input-container>

            </x-form.container>

            <div class="my-10 flex flex-1 justify-between w-full">
                <x-toggle-container for="remember_me">
                    <x-slot:span>
                        <span class="ds-checkbox-mark"></span>
                    </x-slot:span>
                    <x-slot:element>
                        <x-checkbox id="remember_me" name="remember" />
                    </x-slot:element>
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
