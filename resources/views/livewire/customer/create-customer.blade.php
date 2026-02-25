<div class="page-content__container">

    {{--  Content  --}}
    <div class="page-content__hero">
        <div class="page-content__hero-inner">
            <div class="page-content__hero-row">
                <div class="page-content__hero-copy">
                    <h2 class="page-content__title">
                        {{ __('Create Customer') }}
                    </h2>
                    <p class="page-content__subtitle">
                        {{__('Complete the details in two steps.')}}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content__body">
        {{--  Form  --}}
        <div class="page-content__card">
            <form wire:submit.prevent="submit" enctype="multipart/form-data">
                @csrf
                <div class="max-w-5xl mx-auto space-y-8">
                    <x-form.step-progress-bar
                            :current="$step"
                            :steps="[
                                ['key' => 1, 'label' => 'Profile'],
                                ['key' => 2, 'label' => 'Details'],
                            ]"
                    />

                    <x-form.container>
                        {{-- ================= STEP 1 ================= --}}
                        @if ($step === 1)
                            <div wire:key="customer-step-1" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-6">
                                    <div class="flex flex-col items-start gap-4">
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
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-6">
                                        {{-- FULL NAME --}}
                                        <x-form.input-container>
                                            <x-form.label-container label="Full Name" :required="true"/>

                                            <x-input
                                                    wire:model.defer="form.full_name"
                                                    placeholder="John Doe"
                                                    right-icon="user"
                                                    :error="$errors->has('form.full_name')"
                                                    value="{{ old('form.full_name') }}"
                                                    autocomplete="name"
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
                                                    autocomplete="email"
                                            />

                                            <x-input-error for="form.email"/>
                                        </x-form.input-container>

                                        {{-- DOB --}}
                                        <x-form.input-container size="auto" class="md:col-span-2 xl:col-span-1">
                                            <x-form.label-container label="Date of Birth" :required="true"/>

                                            <x-input
                                                    wire:model.defer="form.dob"
                                                    type="date"
                                                    :error="$errors->has('form.dob')"
                                                    value="{{ old('form.dob') }}"
                                                    autocomplete="bday"
                                            />

                                            <x-input-error for="form.dob"/>
                                        </x-form.input-container>
                                    </div>
                                </div>

                                {{--  CUSTOMER ATTIRBUTE SECTION --}}
                                <div class="border border-outline-grey rounded-lg p-6 bg-white space-y-4">
                                    <x-form.label-container label="Attributes"/>

                                    <x-attribute.attribute-card-view :customerAttributes="$form->attributes" />

                                    <x-form.input-container>
                                        @livewire('attribute.attribute-modal')
                                    </x-form.input-container>
                                </div>

                                <div class="flex justify-end">
                                    <x-button size="large" wire:click="nextStep">
                                        Next
                                    </x-button>
                                </div>
                            </div>

                            {{-- ================= STEP 2 ================= --}}
                        @elseif ($step === 2)
                            <div wire:key="customer-step-2" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- PHONE --}}
                                    <x-form.input-container class="md:col-span-2">
                                        <x-form.label-container label="Phone" :required="true"/>

                                        <x-input
                                            id="phone"
                                            id="phone_display"
                                            type="tel"
                                            class="input"
                                            placeholder="+123 123456789"
                                            autocomplete="tel"
                                            wire:model.defer="form.phone"
                                            value="{{ old('form.phone') }}"
                                            autocomplete="tel"
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

                                        <div class="flex flex-wrap gap-6">
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
                                </div>

                                <div class="border border-outline-grey rounded-lg p-6 bg-white space-y-4">
                                    <x-form.label-container label="Skills"/>

                                    <x-skill.skill-card-view :skills="$form->skills" :hide-soft-skills="true" />

                                    <x-form.input-container>
                                        <x-button
                                                size="auto"
                                                wire:click="$dispatch('open-hard-skill-modal')"
                                        >
                                            <x-heroicon name="plus"/>
                                            New Skill
                                        </x-button>
                                        <livewire:skill-modal />
                                    </x-form.input-container>
                                </div>

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
        </div>
    </div>
</div>