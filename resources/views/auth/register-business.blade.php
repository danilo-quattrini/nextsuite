<x-guest-layout>
    <x-authentication-card>
        <x-slot:logo>
            <x-authentication-card-logo />
        </x-slot:logo>
        <x-slot:message>
            <h3 class="mb-4">{{__('Register your business')}}</h3>
            <p class="text-primary-grey text-base font-medium">{{__('Enter your details to start')}}</p>
        </x-slot:message>

        <form method="POST" action="#" enctype="multipart/form-data">
            @csrf
            <x-form.container>
                <!-- AVATAR UPLOAD WRAPPER -->
                <label for="business_photo" class="relative w-32 h-32 block rounded-full overflow-hidden cursor-pointer bg-secondary flex items-center justify-center">
                    <!-- Preview -->
                    <img id="businessPhotoPreview" class="absolute inset-0 w-full h-full object-cover hidden" alt="Preview">

                    <!-- Default icon -->
                    <x-heroicon name="building-office-2" variant="outline" size="xl" id="placeholderIcon" class="text-primary" />
                </label>

                <input id="business_photo" name="business_photo" type="file" class="hidden">
                <x-input-error for="business_photo"/>
                <!-- COMPANY NAME -->
                <x-form.input-container>
                    <x-form.label-container label="{{ __('Company Name') }}" :required="true"/>
                    <x-input id="company_name" name="company_name" :value="old('company_name')" required autofocus autocomplete="username" type="text" right-icon="building-office-2" placeholder="Revelop S.R.L" :error="$errors->has('company_name')"></x-input>
                    <x-input-error for="company_name"/>
                </x-form.input-container>


                <!-- EMPLOYEES -->
                <x-form.input-container>
                    <x-form.label-container label="{{ __('Employees') }}" :required="true"/>
                    <x-input id="employees" name="employees" :value="old('employees')" required autofocus autocomplete="username" type="number" right-icon="users" placeholder="1-10 Employees" :error="$errors->has('employees')"></x-input>
                    <x-input-error for="employees"/>
                </x-form.input-container>

                <!-- PHONE -->
                <x-form.input-container >
                    <x-form.label-container label="{{ __('Phone') }}" :required="true"/>
                    <x-input input_type="phone" id="phone" name="phone" type="tel" class="phone-input" required right-icon="phone" placeholder="3393559210" :error="$errors->has('phone')"></x-input>
                    <x-input-error for="phone"/>
                </x-form.input-container>

                <!-- BUSINESS SELECTOR -->
            </x-form.container>
            <!-- LOGIN BUTTON -->
            <div class="w-[470px] flex items-center justify-between my-10">
                <x-button size="large" variant="rest" type="submit">
                    {{ __('Cancel') }}
                </x-button>
                <x-button size="large" type="submit">
                    {{ __('Next') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fileInput = document.getElementById('business_photo');
            const previewImg = document.getElementById('businessPhotoPreview');
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
