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

                                    <div class="flex flex-col items-start justify-center">
                                        <x-form.avatar-upload
                                                name="customer_photo"
                                                size="xl"
                                                :current="$customer_photo ? $customer_photo->temporaryUrl() : null"
                                                :error="$errors->has('customer_photo')"
                                                wire:model="customer_photo"
                                        />
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

                                        {{-- ROLE --}}

                                        <x-form.input-container>
                                            <x-form.label-container label="Role" :required="true"/>

                                            <x-form.select-wrapper :error="$errors->has('form.role')">
                                                <x-form.select-element
                                                        name="role"
                                                        id="role"
                                                        placeholder="{{ __('Select role') }}"
                                                        wire:model.defer="form.role"
                                                >
                                                    <x-slot:options>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role }}">
                                                                {{ ucfirst($role) }}
                                                            </option>
                                                        @endforeach
                                                    </x-slot:options>
                                                </x-form.select-element>
                                            </x-form.select-wrapper>

                                            <x-input-error for="form.role"/>
                                        </x-form.input-container>
                                    </div>
                                </div>

                                {{--  CUSTOMER ATTIRBUTE SECTION --}}
                                <x-card.card-container
                                        title="Attributes"
                                        icon="user-circle"
                                        size="lg"
                                >

                                    <x-attribute.attribute-card-view :customerAttributes="$form->attributes" />

                                    <x-button
                                            size="full"
                                            variant="outline-dashed"
                                            wire:click="$dispatch('open-add-attribute')"
                                    >
                                        <x-heroicon size="lg" name="plus"/>
                                    </x-button>

                                    <x-form.input-container>
                                        @livewire('attribute.attribute-modal')
                                    </x-form.input-container>
                                </x-card.card-container>

                                {{-- NEXT BUTTON --}}
                                <x-button
                                        size="full"
                                        wire:click="nextStep"
                                >
                                    Next
                                </x-button>
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
                                            <x-form.select-element
                                                    name="nationality"
                                                    id="nationality"
                                                    placeholder="{{ __('Select nationality') }}"
                                                    wire:model.defer="form.nationality"
                                            >
                                                <x-slot:options>
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

                                <x-card.card-container
                                        title="Skills"
                                        icon="star"
                                        size="lg"
                                >
                                    <x-skill.skill-card-view :skills="$form->skills" :hide-soft-skills="true" />

                                    <x-button
                                            variant="outline-dashed"
                                            size="full"
                                            wire:click="$dispatch('open-hard-skill-modal')"
                                    >
                                        <x-heroicon name="plus"/>
                                    </x-button>
                                    <livewire:skill-modal />
                                </x-card.card-container>

                                <div class="flex justify-between gap-sm">
                                    <x-button
                                            variant="rest"
                                            size="full"
                                            wire:click="previousStep"
                                    >
                                        Back
                                    </x-button>
                                    <x-button
                                            size="full"
                                            type="submit"
                                    >
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