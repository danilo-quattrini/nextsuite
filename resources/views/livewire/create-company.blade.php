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
                <x-input id="name" name="name" wire:model="name" required autofocus autocomplete="username" type="text" right-icon="building-office-2" placeholder="Revelop S.R.L" :error="$errors->has('name')" />
                <x-input-error for="name"/>
            </x-form.input-container>


            <!-- EMPLOYEES -->
            <x-form.input-container>
                <x-form.label-container label="{{ __('Employees') }}" :required="true"/>
                <x-input id="employees" name="employees" wire:model="employees" required autofocus autocomplete="username" type="number" right-icon="users" placeholder="1-10 Employees" :error="$errors->has('employees')" />
                <x-input-error for="employees"/>
            </x-form.input-container>

            <!-- PHONE -->
            <x-form.input-container>
                <x-form.label-container label="{{ __('Phone') }}" :required="true"/>
                <div wire:ignore class="w-117.5">
                    <x-input id="phone_display" type="tel" wire:model="phone" required right-icon="phone" placeholder="123456789" :error="$errors->has('phone')" />
                </div>
                <input type="hidden" id="phone" wire:model.defer="phone" />
                <x-input-error for="phone"/>
            </x-form.input-container>

            <!-- COMPANY SECTOR -->
            <x-form.input-container>
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
                                class="absolute z-20 mt-1 w-72 rounded-md border border-outline-grey bg-white shadow-lg p-4"
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

        </x-form.container>
        <!-- CREATE COMPANY BUTTON -->
        <div class="w-117.5 flex items-center justify-center my-10">
            <x-button size="large" type="submit">
                {{ __('Next') }}
            </x-button>
        </div>
    </form>
</div>
