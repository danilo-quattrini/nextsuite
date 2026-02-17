<x-guest-layout>
    <x-authentication-card>
        <x-slot:logo>
            <x-authentication-card-logo />
        </x-slot:logo>
        <x-slot:message>
            <h3 class="mb-4">{{__('Sign Up to getting start')}}</h3>
            <p class="text-primary-grey text-base font-medium">{{__('Enter your personal details first')}}</p>
        </x-slot:message>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf
            <x-form.container>
                <!-- AVATAR UPLOAD WRAPPER -->
                <label for="profile_photo" class="relative w-32 h-32 rounded-full overflow-hidden cursor-pointer bg-secondary flex items-center justify-center">
                    <!-- Preview -->
                    <img id="profilePreview" class="absolute inset-0 w-full h-full object-cover hidden" alt="Preview">

                    <!-- Default icon -->
                    <x-heroicon name="user-plus" variant="outline" size="xl" id="placeholderIcon" class="text-primary" />
                </label>

                <input id="profile_photo" name="profile_photo" type="file" class="hidden">
                <x-input-error for="profile_photo"/>

                <!-- FULL NAME -->
                <x-form.input-container>
                    <x-form.label-container label="{{ __('Full Name') }}" :required="true"/>
                    <x-input id="full_name" name="full_name" :value="old('full_name')" autofocus autocomplete="name" type="text" right-icon="user" placeholder="Jon Doe" :error="$errors->has('full_name')" />
                    <x-input-error for="full_name"/>
                </x-form.input-container>


                <!-- EMAIL -->
                <x-form.input-container>
                    <x-form.label-container label="{{ __('Email') }}" :required="true"/>
                    <x-input id="email" name="email" :value="old('email')" autocomplete="email" type="email" right-icon="envelope" placeholder="Email" :error="$errors->has('email')" />
                    <x-input-error for="email"/>
                </x-form.input-container>

                <!-- PASSWORD -->
                <x-form.input-container>
                    <x-form.label-container label="Password" :required="true"/>
                    <x-input id="password" name="password" type="password" right-icon="eye" placeholder="Password" :error="$errors->has('password')" />
                    <x-input-error for="password"/>
                </x-form.input-container>

                <!-- PASSWORD CONFIRMATION -->
                <x-form.input-container>
                    <x-form.label-container label="{{ __('Confirm Password') }}" :required="true"/>
                    <x-input id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm Password" :error="$errors->has('password_confirmation')" />
                    <x-input-error for="password_confirmation"/>
                </x-form.input-container>
            </x-form.container>

            {{-- TERM & PRIVACY --}}
            <div class="my-10 flex flex-1 justify-between w-full">
                <x-radio name="terms" value="agree" required>
                    I agree to the
                    <a href="{{ route('terms.show') }}" target="_blank">
                        <x-span-link>{{ __('Terms, Conditions') }}</x-span-link>
                    </a>
                    & <br>
                    <a href="{{ route('policy.show') }}" target="_blank">
                        <x-span-link>{{ __('Privacy Policy') }}</x-span-link>
                    </a>
                    <span class="text-secondary-error m-0.5">*</span>
                </x-radio>
                <a href="{{route('login')}}">
                    <x-span-link>{{ __('Already have an account?') }}</x-span-link>
                </a>
            </div>

            <!-- SIGN UP BUTTON -->
            <div class="w-117.5 flex items-center justify-center mb-10">
                <x-button size="large" type="submit">
                    {{ __('Next') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fileInput = document.getElementById('profile_photo');
            const previewImg = document.getElementById('profilePreview');
            const placeholder = document.getElementById('placeholderIcon');

            fileInput.addEventListener('change', () => {
                const file = fileInput.files[0];
                if (!file) return;

                previewImg.src = URL.createObjectURL(file);

                // Show preview
                previewImg.classList.remove('hidden');

                // Hide placeholder icon
                placeholder.classList.add('hidden');
            });
        });
    </script>
</x-guest-layout>
