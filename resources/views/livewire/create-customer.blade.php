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

            {{-- NATIONALITY --}}
            <x-form.input-container>
                <x-form.label-container label="Nationality" :required="true"/>

                <x-form.select-wrapper :error="$errors->has('form.nationality')">
                    <x-form.select-element name="nationality" id="nationality" model="form.nationality">
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

            {{-- DOB --}}
            <x-form.input-container>
                <x-form.label-container label="Date of Birth" :required="true"/>

                <x-input
                        wire:model.defer="form.dob"
                        type="date"
                        :error="$errors->has('form.dob')"
                        value="{{ old('form.dob') }}"
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

            {{-- SKILL VIEW --}}
            @if (!empty($form->skills))
                <div class="mt-6 space-y-4">
                    @foreach ($form->skills as $skillId => $data)
                        <div class="flex justify-between items-center border rounded p-4">
                            <div>
                                <strong>
                                    {{ data_get(collect($skillsByCategory)
                                                ->collapse()
                                                ->firstWhere('id', (int) $skillId), 'name', 'Skill')
                                    }}
                                </strong>
                                <div class="text-sm text-primary-grey">
                                    Level: {{ $data['level'] }} —
                                    Years: {{ $data['years'] }}
                                </div>
                            </div>

                            <x-button
                                    type="button"
                                    variant="error"
                                    wire:click="unset(form.skills.{{ $skillId }})"
                            >
                                Remove
                            </x-button>
                        </div>
                    @endforeach
                </div>
            @endif

            <x-form.input-container>
                <x-form.label-container label="Skill" :required="true"/>

                <x-button
                        size="large"
                        wire:click="$set('showSkillModal', true)"
                >
                    <x-heroicon name="plus" />
                    New Skill
                </x-button>

                {{-- POP UP FOR NEW SKILL --}}
                @if ($showSkillModal)
                    <x-popup-box modal="showSkillModal">

                        <x-slot:header>
                            <x-authentication-card-logo/>
                        </x-slot:header>

                        <x-slot:subheader>
                            {{ __('Add a new Skill') }}
                        </x-slot:subheader>

                        <x-slot:message>
                            {{ __('Here you can add a new skill for the customer in base of your choice') }}
                        </x-slot:message>

                        <x-form.input-container>
                            <x-form.select-wrapper>
                                <x-form.select-element wire:model="selectedSkillId">
                                    <x-slot:options>
                                        <option value="" disabled selected>
                                            Select a skill
                                        </option>

                                        @foreach ($skillsByCategory as $category => $skills)
                                            <optgroup label="{{ $category }}">
                                                @foreach ($skills as $skill)
                                                    <option value="{{ $skill['id'] }}">
                                                        {{ $skill['name'] }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </x-slot:options>
                                </x-form.select-element>
                            </x-form.select-wrapper>
                        </x-form.input-container>

                        {{-- Level --}}
                        <div class="flex justify-between items-center gap-6 my-6">
                            <x-form.input-container size="medium">
                                <x-input type="number" min="1" max="5" wire:model="skillLevel" placeholder="Level" right-icon="star"/>
                                <x-input-error for="skillLevel"/>
                            </x-form.input-container>

                            {{-- Years --}}
                            <x-form.input-container size="medium">
                                <x-input type="number" min="0" wire:model="skillYears" placeholder="Years" right-icon="clock"/>
                                <x-input-error for="skillYears"/>
                            </x-form.input-container>
                        </div>
                        {{-- Actions --}}
                        <div class="flex justify-between gap-4 mt-6">
                            <x-button
                                    type="button"
                                    size="large"
                                    variant="rest"
                                    wire:click="$set('showSkillModal', false)"
                            >
                                Cancel
                            </x-button>

                            <x-button
                                    type="button"
                                    size="large"
                                    wire:click="addSkill"
                            >
                                Add Skill
                            </x-button>
                        </div>

                    </x-popup-box>
                @endif
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