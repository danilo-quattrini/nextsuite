<form wire:submit.prevent="submit" enctype="multipart/form-data">
    @csrf
    <div class="max-w-full mx-auto p-xl space-y-2xl">
        <x-form.step-progress-bar
                :current="$step"
                :steps="[
                    ['key' => 1, 'label' => 'Company Profile'],
                    ['key' => 2, 'label' => 'Details'],
                ]"
        />

        <x-form.container>
            {{-- ================= STEP 1 ================= --}}
            @if ($step === 1)
                <div wire:key="company-step-1" class="space-y-lg">

                    <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-lg">

                        <!-- AVATAR UPLOAD WRAPPER -->
                        <div class="flex flex-col items-start justify-center">
                            <label for="company_photo" class="relative w-40 h-40 flex rounded-full overflow-hidden cursor-pointer bg-secondary items-center justify-center">
                                <!-- Preview -->
                                @if ($company_photo)
                                    <img id="businessPhotoPreview"
                                         src="{{ $company_photo->temporaryUrl() }}"
                                         class="absolute inset-0 w-full h-full object-cover"
                                         alt="Company Photo Preview"
                                    />
                                @else
                                    <!-- Default icon -->
                                    <x-heroicon
                                            name="building-office-2"
                                            id="placeholderIcon"
                                            variant="outline"
                                            size="3xl"
                                            class="text-primary"
                                    />
                                @endif
                                <input id="company_photo" name="company_photo" wire:model="company_photo" type="file" class="hidden" />
                            </label>

                            {{--  ERROR MESSAGE --}}
                            <x-input-error for="company_photo"/>
                        </div>

                        {{--  INPUT FIELDS  --}}
                        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-md">

                            <!-- COMPANY NAME -->
                            <x-form.input-container size="auto">
                                <x-form.label-container label="{{ __('Company Name') }}" :required="true"/>
                                <x-input id="name" name="name" wire:model="name" autofocus autocomplete="name" type="text" right-icon="building-office-2" placeholder="Revelop S.R.L" :error="$errors->has('name')" />
                                <x-input-error for="name"/>
                            </x-form.input-container>

                            <!-- COMPANY EMAIL -->
                            <x-form.input-container size="auto">
                                <x-form.label-container label="{{ __('Email') }}" :required="true"/>
                                <x-input id="email" name="email" wire:model="email" autocomplete="email" type="text" right-icon="envelope" placeholder="revelopsrl.com@gmail.com" :error="$errors->has('email')" />
                                <x-input-error for="email"/>
                            </x-form.input-container>

                            <!-- COMPANY WEBSITE -->
                            <x-form.input-container  size="auto">
                                <x-form.label-container label="{{ __('Website') }}"/>
                                <x-input id="website" name="website" wire:model="website" type="text" right-icon="globe-alt" placeholder="https://example.com" :error="$errors->has('website')" />
                                <x-input-error for="website"/>
                            </x-form.input-container>

                            <!-- COMPANY VAT -->
                            <x-form.input-container size="auto">
                                <x-form.label-container label="{{ __('VAT Number') }}" :required="true"/>
                                <x-input id="vat_number" name="vat_number" wire:model="vat_number" type="text" right-icon="identification" placeholder="ABC1234567890" :error="$errors->has('vat_number')" />
                                <x-input-error for="vat_number"/>
                            </x-form.input-container>

                            <!-- NEXT STEP BUTTON -->
                            <div class="col-start-1 lg:col-start-2">
                                <x-button
                                        size="full"
                                        wire:click="nextStep"
                                >
                                    Next
                                </x-button>
                            </div>

                        </div>
                    </div>
                </div>
                {{-- ================= STEP 2 ================= --}}
            @elseif($step === 2)
                <div wire:key="company-step-2" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- COMPANY CITY -->
                            <x-form.input-container>
                                <x-form.label-container label="{{ __('City') }}" :required="true"/>
                                <x-input id="city" name="city" wire:model="city" type="text" right-icon="building-library" placeholder="Random City" :error="$errors->has('city')" />
                                <x-input-error for="city"/>
                            </x-form.input-container>


                            <!-- COMPANY NUMBER -->
                            <x-form.input-container>
                                <x-form.label-container label="{{ __('Phone number') }}" :required="true"/>
                                <div wire:ignore class="w-full">
                                    <x-input id="phone_display" type="tel" wire:model="phone" required right-icon="phone" placeholder="123456789" :error="$errors->has('phone')" />
                                </div>
                                <input type="hidden" id="phone" wire:model.defer="phone" />
                                <x-input-error for="phone"/>
                            </x-form.input-container>

                            <!-- COMPANY ADDRESS -->
                            <x-form.input-container class="col-span-2 w-full">
                                <x-form.label-container label="{{ __('Address') }}" :required="true"/>
                                <x-input id="address_line" name="address_line" wire:model="address_line" type="text" right-icon="map-pin" placeholder="Street n°8" :error="$errors->has('address_line')" />
                                <x-input-error for="address_line"/>
                            </x-form.input-container>
                    </div>
                    <!-- COMPANY SECTOR -->
                    <x-form.input-container class="md:col-span-2">
                        <x-form.label-container label="{{ __('Select Field:') }}" :required="true"/>

                        {{-- FIELD SHOW --}}
                        @if($selectedFields)
                            <div class="grid grid-cols-5 gap-2">
                                @foreach($selectedFields as $fieldId => $field)
                                        <x-tag size="default" variant="white">

                                            <x-slot:trailing>
                                                <button
                                                        type="button"
                                                        wire:click="removeArrayItem('selectedFields', {{ $fieldId }})"
                                                        class="text-primary-grey hover:text-secondary-error transition cursor-pointer"
                                                >
                                                    <x-heroicon name="x-circle" size="sm"/>
                                                </button>
                                            </x-slot:trailing>

                                            {{ $fields->firstWhere('id', $fieldId)?->name }}

                                        </x-tag>
                                @endforeach
                            </div>
                        @endif

                        <x-button variant="outline-dashed" size="auto" wire:click="toggleFieldDropdown">
                            <x-heroicon name="plus"/>
                            New Field
                        </x-button>

                        {{-- DROPDOWN --}}
                        <div class="relative mt-1">
                            @if($showFieldDropdown)
                                <div
                                        class="absolute z-50 mt-1 w-72 rounded-md border border-outline-grey bg-white shadow-lg p-4"
                                >
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($fields as $item)
                                            @php
                                                $isSelected = array_key_exists($item->id, $selectedFields);
                                            @endphp

                                            <button
                                                    type="button"
                                                    wire:click="selectField({{ $item->id }})"
                                                    class="px-3 py-1.5 cursor-pointer rounded-md text-sm font-medium border transition
                                                    {{ $isSelected
                                                        ? 'bg-primary text-white border-primary'
                                                        : 'bg-white text-black border-outline-grey hover:bg-gray-100'
                                                    }}"
                                            >
                                                {{ $item->name }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        <x-input-error for="selectedFields"/>
                    </x-form.input-container>
                    <div class="flex justify-between">
                        <x-button variant="rest" size="large" wire:click="previousStep">
                            Back
                        </x-button>
                        <x-button size="large" type="submit">
                            Create
                        </x-button>
                    </div>
                </div>
            @endif
        </x-form.container>
    </div>
</form>
