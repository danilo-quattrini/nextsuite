<x-guest-layout>
    <x-authentication-card>
        <x-slot:logo>
            <x-authentication-card-logo />
        </x-slot:logo>
        <x-slot:message>
            <h4 class="mb-2">{{ __('Create your account') }}</h4>
            <p class="text-primary-grey text-sm font-normal">{{ __('Fill in your details to get started') }}</p>
        </x-slot:message>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <x-form.container>

                <!-- AVATAR UPLOAD -->
                <div class="flex justify-center items-center">
                    <x-form.avatar-upload
                            id="profile_photo"
                            size="lg"
                    />
                </div>

                <!-- FULL NAME -->
                <x-form.input-container
                        size="full"
                >
                    <x-form.label-container label="{{ __('Full Name') }}" :required="true"/>
                    <x-input id="full_name" name="full_name" :value="old('full_name')" autofocus autocomplete="name" type="text" right-icon="user" placeholder="Jon Doe" :error="$errors->has('full_name')" />
                    <x-input-error for="full_name"/>
                </x-form.input-container>

                <!-- EMAIL -->
                <x-form.input-container
                        size="full"
                >
                    <x-form.label-container label="{{ __('Email') }}" :required="true"/>
                    <x-input id="email" name="email" :value="old('email')" autocomplete="email" type="email" right-icon="envelope" placeholder="you@example.com" :error="$errors->has('email')" />
                    <x-input-error for="email"/>
                </x-form.input-container>

                <!-- PASSWORD -->
                <x-form.input-container
                        size="full"
                >
                    <x-form.label-container label="{{ __('Password') }}" :required="true"/>
                    <x-input id="password" name="password" type="password" right-icon="eye" placeholder="••••••••" :error="$errors->has('password')" />
                    <x-input-error for="password"/>
                </x-form.input-container>

                <!-- PASSWORD CONFIRMATION -->
                <x-form.input-container
                        size="full"
                >
                    <x-form.label-container label="{{ __('Confirm Password') }}" :required="true"/>
                    <x-input id="password_confirmation" name="password_confirmation" type="password" placeholder="••••••••" :error="$errors->has('password_confirmation')" />
                    <x-input-error for="password_confirmation"/>
                </x-form.input-container>

            </x-form.container>

            <!-- TERMS & LOGIN LINK -->
            <div class="mt-6 mb-8 flex items-start justify-between w-full gap-4">
                <x-toggle-container>
                    <x-slot:element>
                        <x-form.checkbox id="terms" name="terms" value="on" size="md" :wrap="true" />
                    </x-slot:element>
                    <x-slot:span></x-slot:span>
                    <span class="text-sm text-primary-grey leading-snug">
                        {{ __('I agree to the') }}
                        <a href="{{ route('terms.show') }}" target="_blank">
                            <x-span-link>{{ __('Terms & Conditions') }}</x-span-link>
                        </a>
                        {{ __('and') }}
                        <a href="{{ route('policy.show') }}" target="_blank">
                            <x-span-link>{{ __('Privacy Policy') }}</x-span-link>
                        </a>
                        <span class="text-secondary-error">*</span>
                    </span>
                    <x-input-error for="terms"/>
                </x-toggle-container>

                <a href="{{ route('login') }}" class="shrink-0">
                    <x-span-link>{{ __('Already have an account?') }}</x-span-link>
                </a>
            </div>

            <!-- SUBMIT -->
            <div class="w-full flex items-center justify-center mb-8">
                <x-button size="full" type="submit">
                    {{ __('Create account') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fileInput   = document.getElementById('profile_photo');
            const previewImg  = document.getElementById('profilePreview');
            const placeholder = document.getElementById('placeholderIcon');

            fileInput.addEventListener('change', () => {
                const file = fileInput.files[0];
                if (!file) return;
                previewImg.src = URL.createObjectURL(file);
                previewImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
            });
        });
    </script>
</x-guest-layout>