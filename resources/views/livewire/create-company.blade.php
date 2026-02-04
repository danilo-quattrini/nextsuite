<form wire:submit.prevent="submit" enctype="multipart/form-data">
    @csrf
    <div class="max-w-5xl mx-auto space-y-8">
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
                <div wire:key="company-step-1" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-6">

                        <!-- AVATAR UPLOAD WRAPPER -->
                        <div class="flex flex-col items-start gap-4">
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
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-6">
                                <!-- COMPANY NAME -->
                                <x-form.input-container>
                                    <x-form.label-container label="{{ __('Name') }}" :required="true"/>
                                    <x-input id="name" name="name" wire:model="name" autofocus autocomplete="username" type="text" right-icon="building-office-2" placeholder="Revelop S.R.L" :error="$errors->has('name')" />
                                    <x-input-error for="name"/>
                                </x-form.input-container>

                                <!-- COMPANY EMAIL -->
                                <x-form.input-container>
                                    <x-form.label-container label="{{ __('Email') }}" :required="true"/>
                                    <x-input id="email" name="email" wire:model="email" autocomplete="email" type="text" right-icon="envelope" placeholder="revelopsrl.com@gmail.com" :error="$errors->has('email')" />
                                    <x-input-error for="email"/>
                                </x-form.input-container>

                                <!-- COMPANY VAT -->
                                <x-form.input-container class="col-span-2 w-full">
                                    <x-form.label-container label="{{ __('VAT Number') }}" :required="true"/>
                                    <x-input id="vat_number" name="vat_number" wire:model="vat_number" type="text" right-icon="identification" placeholder="ABC1234567890" :error="$errors->has('vat_number')" />
                                    <x-input-error for="vat_number"/>
                                </x-form.input-container>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <x-button size="large" wire:click="nextStep">
                            Next
                        </x-button>
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
