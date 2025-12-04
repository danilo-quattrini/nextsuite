<x-guest-layout>
    <x-authentication-card>
        <x-slot:logo>
            <x-authentication-card-logo />
        </x-slot:logo>
        <x-slot:message>
            <h3 class="mb-4">{{__('Sign Up to getting start')}}</h3>
            <p class="text-primary-grey text-base font-medium">{{__('Enter your details to start')}}</p>
        </x-slot:message>
        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <x-form.container>
                <!-- FULL NAME -->
                <x-form.input-container>
                    <x-form.label-container label="{{ __('Full Name') }}"/>
                    <x-input id="full_name" :value="old('full_name')" required autofocus autocomplete="username" type="text" right-icon="user" placeholder="Jon Doe"></x-input>
                </x-form.input-container>


                <!-- EMAIL -->
                <x-form.input-container>
                    <x-form.label-container label="{{ __('Email') }}"/>
                    <x-input id="email" name="email" :value="old('email')" required autofocus autocomplete="username" type="email" right-icon="envelope" placeholder="Email"></x-input>
                </x-form.input-container>

                <!-- PASSWORD -->
                <x-form.input-container>
                    <x-form.label-container label="Password"/>
                    <x-input id="password" name="password" required autofocus autocomplete="username" type="password" right-icon="eye" placeholder="Password"></x-input>
                </x-form.input-container>

                <!-- PASSWORD CONFIRMATION -->
                <x-form.input-container>
                    <x-form.label-container label="{{ __('Confirm Password') }}"/>
                    <x-input id="password_confirmation" name="password_confirmation" required autofocus autocomplete="new-password" type="password" placeholder="Confirm Password"></x-input>
                </x-form.input-container>
            </x-form.container>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif
            <div class="my-10 flex flex-1 justify-between w-full">
                <x-toggle-container>
                    <x-slot:span>
                        <span class="ds-checkbox-mark"></span>
                    </x-slot:span>
                    <x-slot:element>
                        <x-checkbox name="remember" />
                    </x-slot:element>
                    {{ __('I agree with terms & conditions') }}
                </x-toggle-container>
                <a href="{{route('login')}}">
                    <x-span-link>{{ __('Already have an account?') }}</x-span-link>
                </a>
            </div>
            <!-- LOGIN BUTTON -->
            <div class="w-[470px] flex items-center justify-center mb-10">
                <x-button size="large" type="submit">
                    {{ __('Sing Up') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
