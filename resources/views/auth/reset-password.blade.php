<x-guest-layout>
    <x-authentication-card>
        <x-slot:logo>
            <x-authentication-card-logo />
        </x-slot:logo>
        <x-slot:message>
            <h3 class="mb-4">{{__('Recover Password')}}</h3>
            <p class="text-primary-grey text-base font-medium">Create a new strong password</p>
        </x-slot:message>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <x-form.container>

                <!-- EMAIL -->
                <x-form.input-container>
                    <x-form.label-container label="{{ __('Email') }}"/>
                    <x-input id="email" name="email" :value="old('email', $request->email)" autofocus autocomplete="username" type="email" right-icon="envelope" placeholder="Email" :error="$errors->has('email')"></x-input>
                    <x-input-error for="email"/>
                </x-form.input-container>

                <!-- PASSWORD -->
                <x-form.input-container>
                    <x-form.label-container label="{{ __('Password') }}" />
                    <x-input id="password" name="password" type="password" right-icon="eye" placeholder="Password" autocomplete="new-password" :error="$errors->has('password')" />
                    <x-input-error for="password"/>
                </x-form.input-container>

                <!-- PASSWORD CONFIRMATION -->
                <x-form.input-container>
                    <x-form.label-container label="{{ __('Confirm Password') }}" :required="true"/>
                    <x-input id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm Password" autocomplete="new-password" :error="$errors->has('password_confirmation')"/>
                    <x-input-error for="password_confirmation"/>
                </x-form.input-container>

            </x-form.container>

            <!-- RESET PASSWORD BUTTON -->
            <div class="w-[470px] flex items-center justify-center my-10">
                <x-button size="large" type="submit">
                    {{ __('Reset Password') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
