<form wire:submit.prevent="submit" enctype="multipart/form-data">
    <x-form.container>

        {{-- ================= STEP 1 ================= --}}
        @if ($step === 1)

            {{-- AVATAR --}}
            <label
                    for="customer_photo"
                    class="relative w-32 h-32 flex rounded-full overflow-hidden cursor-pointer bg-secondary items-center justify-center"
            >
                @if ($form->customer_photo)
                    <img
                            src="{{ $form->customer_photo->temporaryUrl() }}"
                            class="absolute inset-0 w-full h-full object-cover"
                            alt="Customer Photo Preview"
                    />
                @else
                    <x-heroicon name="user" variant="outline" size="xl" class="text-primary"/>
                @endif

                <input
                        id="customer_photo"
                        type="file"
                        wire:model="form.customer_photo"
                        class="hidden"
                />
            </label>
            <x-input-error for="form.customer_photo"/>

            {{-- FULL NAME --}}
            <x-form.input-container>
                <x-form.label-container label="Full Name" :required="true"/>

                <x-input
                        wire:model.defer="form.full_name"
                        placeholder="John Doe"
                        right-icon="user"
                        :error="$errors->has('form.full_name')"
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
                />

                <x-input-error for="form.email"/>
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
                />

                <x-input-error for="form.phone"/>
            </x-form.input-container>

            {{-- DOB --}}
            <x-form.input-container>
                <x-form.label-container label="Date of Birth" :required="true"/>

                <x-input
                        wire:model.defer="form.dob"
                        type="date"
                        :error="$errors->has('form.dob')"
                />

                <x-input-error for="form.dob"/>
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
                                        wire:model="form.gender"
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

            <div class="flex justify-center mt-10">
                <x-button size="large" type="submit">
                    Create
                </x-button>
            </div>

        @endif

    </x-form.container>
</form>