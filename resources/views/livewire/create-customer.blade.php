<form wire:submit.prevent="submit" enctype="multipart/form-data">
        @csrf
        <x-form.container>
            <!-- AVATAR UPLOAD WRAPPER -->
            <label for="customer_photo" class="relative w-32 h-32 flex rounded-full overflow-hidden cursor-pointer bg-secondary items-center justify-center">
                <!-- Preview -->
                @if ($customer_photo)
                    <img id="customerPhotoPreview"
                         src="{{ $customer_photo->temporaryUrl() }}"
                         class="absolute inset-0 w-full h-full object-cover" alt="Business Photo Preview" />
                @else
                    <!-- Default icon -->
                    <x-heroicon name="user" variant="outline" size="xl" id="placeholderIcon" class="text-primary" />
                @endif
                <input id="customer_photo" name="customer_photo" wire:model="customer_photo" type="file" class="hidden" />
            </label>

            <!-- CUSTOMER NAME -->
            <x-form.input-container>
                <x-form.label-container label="{{ __('Full Name') }}" :required="true"/>
                <x-input id="name" name="name" wire:model="full_name" required autofocus autocomplete="username" type="text" right-icon="user" placeholder="John Doe" :error="$errors->has('full_name')"></x-input>
                <x-input-error for="full_name"/>
            </x-form.input-container>

            <!-- CUSTOMER EMAIL -->
            <x-form.input-container>
                <x-form.label-container label="{{ __('Email') }}" :required="true"/>
                <x-input id="name" name="name" wire:model="email" required autofocus autocomplete="username" type="text" right-icon="envelope" placeholder="johndoe@gmail.com" :error="$errors->has('name')"></x-input>
                <x-input-error for="name"/>
            </x-form.input-container>

        </x-form.container>
        <!-- CREATE CUSTOMER BUTTON -->
        <div class="w-[470px] flex items-center justify-center my-10">
            <x-button size="large" type="submit">
                {{ __('Create') }}
            </x-button>
        </div>
</form>