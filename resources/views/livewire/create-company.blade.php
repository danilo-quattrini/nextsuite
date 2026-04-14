<div wire:submit.prevent="submit" enctype="multipart/form-data">
    @csrf
    <div class="max-w-full mx-auto p-xl space-y-2xl">
        <x-form.step-progress-bar
                :current="$step"
                :steps="[
                    ['key' => 1, 'label' => 'Company Profile'],
                    ['key' => 2, 'label' => 'Details'],
                    ['key' => 3, 'label' => 'Invite Employee'],
                ]"
        />

        <x-form.container>
            {{-- ================= STEP 1 ================= --}}
            @if ($step === 1)
                <div wire:key="company-step-1" class="space-y-lg">

                    <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-lg">

                        <!-- AVATAR UPLOAD WRAPPER -->
                        <div class="flex items-center justify-center">
                            <x-form.avatar-upload
                                    name="company_photo"
                                    size="xl"
                                    icon="building-office-2"
                                    :current="$company_photo ? $company_photo->temporaryUrl() : null"
                                    :error="$errors->has('company_photo')"
                                    wire:model="company_photo"
                            />
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
                    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-md">

                        <!-- COMPANY CITY -->
                        <x-form.input-container size="auto">
                            <x-form.label-container label="{{ __('City') }}" :required="true"/>
                            <x-input id="city" name="city" wire:model="city" type="text" right-icon="building-library" placeholder="Random City" :error="$errors->has('city')" />
                            <x-input-error for="city"/>
                        </x-form.input-container>


                        <!-- COMPANY NUMBER -->
                        <x-form.input-container size="auto">
                            <x-form.label-container label="{{ __('Phone number') }}" :required="true"/>
                                <x-input
                                        id="phone"
                                        name="phone"
                                        wire:model="phone"
                                        type="text"
                                        right-icon="phone"
                                        placeholder="123-4567-8901"
                                        :error="$errors->has('phone')"
                                />
                            <x-input-error for="phone"/>
                        </x-form.input-container>

                        <!-- COMPANY ADDRESS -->
                        <x-form.input-container size="auto" class="col-span-1 lg:col-span-2">
                            <x-form.label-container label="{{ __('Address') }}" :required="true"/>
                            <x-input id="address_line" name="address_line" wire:model="address_line" type="text" right-icon="map-pin" placeholder="Street n°8" :error="$errors->has('address_line')" />
                            <x-input-error for="address_line"/>
                        </x-form.input-container>

                        <!-- COMPANY FIELD SELECTOR -->
                        <x-form.input-container size="auto" class="col-span-1 lg:col-span-2">
                            <x-form.label-container label="{{ __('Select Field:') }}" :required="true"/>

                            {{-- FIELD SHOW --}}
                            @if($selectedFields)
                                <div class="grid grid-cols-3 gap-xs">
                                    @foreach($selectedFields as $fieldId => $field)
                                        <x-tag
                                                variant="white"
                                                :dismissible="true"
                                                size="md"
                                        >
                                            {{ $fields->firstWhere('id', $fieldId)?->name }}
                                        </x-tag>
                                    @endforeach
                                </div>
                            @endif

                            {{--   DROPDOWN COMPONENT --}}
                            <x-dropdown
                                    align="left"
                                    width="80"
                                    content-classes="flex flex-wrap bg-white p-sm gap-xs"
                            >
                                <x-slot:trigger>
                                    {{--  BUTTON TO ADD FIELDS --}}
                                    <x-button
                                            variant="outline-dashed"
                                            size="auto"
                                    >
                                        <x-heroicon
                                                name="plus"
                                                size="lg"
                                        />
                                        New Field
                                    </x-button>
                                </x-slot:trigger>

                                {{--   DROPDOWN FIELDS --}}
                                <x-slot:content>
                                    @foreach($fields as $item)
                                        @php
                                            $isSelected = array_key_exists($item->id, $selectedFields);
                                        @endphp
                                        <div class="flex flex-wrap gap-2">
                                            <button
                                                    type="button"
                                                    wire:click="selectField({{ $item->id }})"
                                                    class="px-4 py-2 cursor-pointer rounded-md text-sm font-medium border transition
                                                        {{ $isSelected
                                                            ? 'border-primary text-primary'
                                                            : 'bg-white text-black border-outline-grey hover:bg-gray-100'
                                                        }}"
                                            >
                                                {{ $item->name }}
                                            </button>
                                        </div>
                                    @endforeach
                                </x-slot:content>
                            </x-dropdown>

                            {{--   ERROR DROPDOWN FIELDS --}}
                            <x-input-error for="selectedFields"/>
                        </x-form.input-container>

                        <!-- BUTTTON TO BACK -->
                        <x-form.input-container size="auto"  class="col-span-1">
                            <x-button
                                    variant="rest"
                                    size="full"
                                    wire:click="previousStep"
                            >
                                Back
                            </x-button>
                        </x-form.input-container>

                        <x-form.input-container size="auto"  class="col-span-1">
                            <x-button
                                    size="full"
                                    wire:click="nextStep"
                            >
                                Next
                            </x-button>
                        </x-form.input-container>
                    </div>
                </div>
                {{-- ================= STEP 3 ================= --}}
            @elseif($step === 3)
                <div wire:key="company-step-3" class="space-y-6">

                    <!-- TABLE WITH THE USERS -->
                    <x-data-table
                            :table-data="$this->users"
                            :columns="$this->tableColumns"
                            photo-field="profile_photo_url"
                            name-field="full_name"
                            empty-message="Sorry but nothing it is there not there"
                    />

                    {{--   ERROR DROPDOWN FIELDS --}}
                    <x-input-error for="selectedRows"/>
                    {{-- Catches any wildcard errors --}}
                    @error('selectedRows.*')
                    <span class="text-secondary-error text-sm">{{ $message }}</span>
                    @enderror


                    @if(count($this->users) != 0)
                        <div class="page-content__pagination">
                            {{ $this->users->links('components.pagination') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-md">
                        <!-- BUTTON TO GO BACK -->
                        <x-form.input-container size="auto"  class="col-span-1">
                            <x-button
                                    variant="rest"
                                    size="full"
                                    wire:click="previousStep"
                            >
                                Back
                            </x-button>
                        </x-form.input-container>

                        <!-- BUTTTON TO CREATE THE COMPANY -->
                        <x-form.input-container size="auto" class="col-span-1">
                            <x-button
                                    size="full"
                                    wire:click="submit"
                            >
                                Create
                            </x-button>
                        </x-form.input-container>
                    </div>
                </div>
            @endif
        </x-form.container>
    </div>
</div>
