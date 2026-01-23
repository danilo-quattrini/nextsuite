<form wire:submit.prevent="submit" enctype="multipart/form-data">
    <x-form.container>

        {{-- ================= STEP 1 ================= --}}
        @if ($step === 1)

            {{-- AVATAR --}}
            <label
                    for="customer_photo"
                    class="relative w-32 h-32 flex rounded-full overflow-hidden cursor-pointer bg-secondary items-center justify-center"
            >
                @if ($customer_photo)
                    <img
                            src="{{ $customer_photo->temporaryUrl() }}"
                            class="absolute inset-0 w-full h-full object-cover"
                            alt="Customer Photo Preview"
                    />
                @else
                    <x-heroicon name="user-plus" variant="outline" size="xl" class="text-primary"/>
                @endif

                <input
                        id="customer_photo"
                        type="file"
                        wire:model="customer_photo"
                        class="hidden"
                />
            </label>
            <x-input-error for="customer_photo"/>

            {{-- FULL NAME --}}
            <x-form.input-container>
                <x-form.label-container label="Full Name" :required="true"/>

                <x-input
                        wire:model.defer="form.full_name"
                        placeholder="John Doe"
                        right-icon="user"
                        :error="$errors->has('form.full_name')"
                        value="{{ old('form.full_name') }}"
                />

                <x-input-error for="form.full_name"/>
            </x-form.input-container>

            {{-- EMAIL --}}
            <x-form.input-container>
                <x-form.label-container label="Email" :required="true"/>

                <x-input
                        wire:model.defer="form.email"
                        placeholder="johndoe@gmail.com"
                        right-icon="envelope"
                        :error="$errors->has('form.email')"
                        value="{{ old('form.email') }}"
                />

                <x-input-error for="form.email"/>
            </x-form.input-container>

            {{-- DOB --}}
            <x-form.input-container>
                <x-form.label-container label="Date of Birth" :required="true"/>

                <x-input
                        wire:model.defer="form.dob"
                        type="date"
                        :error="$errors->has('form.dob')"
                        value="{{ old('form.dob') }}"
                />
            </x-form.input-container>

            {{-- ATTRIBUTES --}}
            <x-form.label-container label="Attributes"/>

            <x-attribute.attribute-card-view :customerAttributes="$form->attributes" />

            <x-form.input-container>
                @livewire('attribute.attribute-modal')
            </x-form.input-container>

            <x-button size="large" wire:click="nextStep">
                Next
            </x-button>

            {{-- ================= STEP 2 ================= --}}
        @elseif ($step === 2)

            {{-- PHONE --}}
            <x-form.input-container>
                <x-form.label-container label="Phone" :required="true"/>

                {{-- IMPORTANT: no wire:ignore here --}}
                <x-input
                        wire:model.defer="form.phone"
                        type="tel"
                        placeholder="+123 123456789"
                        right-icon="phone"
                        :error="$errors->has('form.phone')"
                        value="{{ old('form.phone') }}"
                />

                <x-input-error for="form.phone"/>
            </x-form.input-container>

            {{-- NATIONALITY --}}
            <x-form.input-container>
                <x-form.label-container label="Nationality" :required="true"/>

                <x-form.select-wrapper :error="$errors->has('form.nationality')">
                    <x-form.select-element name="nationality" id="nationality" wire:model.defer="form.nationality">
                        <x-slot:options>
                            <option value="" disabled selected hidden>
                                Select nationality
                            </option>
                            @foreach ($nationalities as $nation)
                                <option value="{{ $nation['name'] }}">
                                    {{ $nation['flag'] }} {{ $nation['name'] }}
                                </option>
                            @endforeach
                        </x-slot:options>
                    </x-form.select-element>
                </x-form.select-wrapper>

                <x-input-error for="form.nationality"/>
            </x-form.input-container>

            {{-- GENDER --}}
            <x-form.input-container>
                <x-form.label-container label="Gender" :required="true"/>

                <div class="flex gap-6">
                    @foreach (['man' => 'Man', 'woman' => 'Woman', 'other' => 'Other'] as $value => $label)
                        <x-radio-container>
                            <x-slot:element>
                                <input
                                        type="radio"
                                        value="{{ $value }}"
                                        wire:model.defer="form.gender"
                                        class="ds-radio-input"
                                />
                            </x-slot:element>
                            <x-slot:span>
                                <span class="ds-radio-mark"></span>
                            </x-slot:span>
                            {{ __($label) }}
                        </x-radio-container>
                    @endforeach
                </div>

                <x-input-error for="form.gender"/>
            </x-form.input-container>

            {{-- SKILLS --}}
            <x-form.label-container label="Skills"/>

            <x-skill.skill-card-view :skills="$form->skills" />

            <x-form.input-container>
                @livewire('skill-modal')
            </x-form.input-container>

        @endif
    </x-form.container>
    @if($step === 2)
        <div class="flex justify-between mt-10">
            <x-button variant="rest" size="large" wire:click="previousStep">
                Back
            </x-button>
            <x-button size="large" type="submit">
                Create
            </x-button>
        </div>
    @endif
</form>