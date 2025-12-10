<div>
    <form wire:submit.prevent="submit" enctype="multipart/form-data">
        @csrf
        <x-form.container>
            <!-- AVATAR UPLOAD WRAPPER -->
            <label for="business_photo" class="relative w-32 h-32 flex rounded-full overflow-hidden cursor-pointer bg-secondary items-center justify-center">
                <!-- Preview -->
                @if ($business_photo)
                    <img id="businessPhotoPreview"
                         src="{{ $business_photo->temporaryUrl() }}"
                         class="absolute inset-0 w-full h-full object-cover" alt="Business Photo Preview" />
                @else
                    <!-- Default icon -->
                    <x-heroicon name="building-office-2" variant="outline" size="xl" id="placeholderIcon" class="text-primary" />
                @endif
                <input id="business_photo" name="business_photo" wire:model="business_photo" type="file" class="hidden" />
            </label>
            <x-input-error for="business_photo"/>
            <!-- COMPANY NAME -->
            <x-form.input-container>
                <x-form.label-container label="{{ __('Company Name') }}" :required="true"/>
                <x-input id="name" name="name" wire:model="name" required autofocus autocomplete="username" type="text" right-icon="building-office-2" placeholder="Revelop S.R.L" :error="$errors->has('name')"></x-input>
                <x-input-error for="name"/>
            </x-form.input-container>


            <!-- EMPLOYEES -->
            <x-form.input-container>
                <x-form.label-container label="{{ __('Employees') }}" :required="true"/>
                <x-input id="employees" name="employees" wire:model="employees" required autofocus autocomplete="username" type="number" right-icon="users" placeholder="1-10 Employees" :error="$errors->has('employees')"></x-input>
                <x-input-error for="employees"/>
            </x-form.input-container>

            <!-- PHONE -->
            <x-form.input-container>
                <x-form.label-container label="{{ __('Phone') }}" :required="true"/>
                <x-input wire:ignore input_type="phone" id="phone" name="phone" wire:model="phone" required right-icon="phone" placeholder="123456789" :error="$errors->has('phone')"></x-input>
                <x-input-error for="phone"/>
            </x-form.input-container>

            <!-- COMPANY SECTOR -->
            <x-form.input-container>
                <x-form.label-container label="{{ __('Select Company:') }}" :required="true"/>
                <x-form.select-wrapper :error="$errors->has('field')">
                        <x-form.select-element  name="field" id="field"  :options="$fields"/>
                </x-form.select-wrapper>
                <x-input-error for="field"/>
            </x-form.input-container>

            <!-- BUSINESS SELECTOR -->
        </x-form.container>
        <!-- LOGIN BUTTON -->
        <div class="w-[470px] flex items-center justify-between my-10">
            <x-button href="/" size="large" variant="rest">
                {{ __('Cancel') }}
            </x-button>
            <x-button size="large" type="submit">
                {{ __('Next') }}
            </x-button>
        </div>
    </form>
</div>
