<x-guest-layout>
    <x-authentication-card>
        <x-slot:logo>
            <x-authentication-card-logo />
        </x-slot:logo>

        <x-slot:message>
            {{ __('This is a secure area of the application.') }}<br>
            {{ __(' Please confirm your password before continuing.') }}
        </x-slot:message>
        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <x-form.container>
                <!-- PASSWORD -->
                <x-form.input-container>
                    <x-form.label-container label="{{ __('Password') }}" />
                    <x-input id="password" name="password" type="password" right-icon="eye" placeholder="Password" autocomplete="current-password" :error="$errors->has('password')" />
                    <x-input-error for="password"/>
                </x-form.input-container>
            </x-form.container>

            <!-- RESET PASSWORD BUTTON -->
            <div class="w-[470px] flex items-center justify-center my-10">
                <x-button size="large" type="submit">
                    {{ __('Confirm') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
