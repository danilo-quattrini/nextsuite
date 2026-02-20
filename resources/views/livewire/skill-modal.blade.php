<div>

    {{-- BUTTON TO ADD A NEW SKILL  --}}
    <x-button
            size="auto"
            wire:click="$set('showModal', true)"
    >
        <x-heroicon name="plus"/>
        New Skill
    </x-button>

    @if ($showModal)
        <x-popup-box modal="showModal">

            <x-slot:header>
                <x-authentication-card-logo/>
            </x-slot:header>

            <x-slot:subheader>
                {{ __('Add a new Skill') }}
            </x-slot:subheader>

            <x-slot:message>
                {{ __('Here you can add a new skill for the customer in base of your choice') }}
            </x-slot:message>
            <x-form.container>
                <x-form.input-container>
                    <x-form.label-container label="Skill" :required="true"/>

                    <x-form.select-wrapper :error="$errors->has('selectedSkillId')">
                        <x-form.select-element wire:model.live="selectedSkillId">
                            <x-slot:options>
                                <option value="" hidden>
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

                    <x-input-error for="selectedSkillId"/>
                </x-form.input-container>

                @if(!empty($selectedSkillId))
                    @if($showYearsInput)
                        {{-- Years --}}
                        <x-form.input-container size="auto">
                            <x-form.label-container label="Years" :required="true"/>
                            <x-input type="number" min="0" wire:model="skillYears" placeholder="Years"
                                     right-icon="clock" :error="$errors->has('skillYears')"/>
                            <x-input-error for="skillYears"/>
                        </x-form.input-container>
                    @endif
                    {{-- Level --}}
                    <x-form.input-container size="auto">
                        <x-form.label-container label="Level" :required="true"/>
                        <x-form.slider
                                id="skillLevel"
                                name="skillLevel"
                                wire:model.live="skillLevel"
                                :step="25"
                                :markers="[
                                    ['value' => 0, 'label' => 'Novice'],
                                    ['value' => 25, 'label' => 'Intermedian'],
                                    ['value' => 50, 'label' => 'Advanced'],
                                    ['value' => 75, 'label' => 'Expert'],
                                    ['value' => 100, 'label' => 'Professional'],
                                ]"
                        />
                        <x-input-error for="skillLevel"/>
                    </x-form.input-container>
                @endif
            </x-form.container>
            {{-- Actions --}}
            <div class="flex justify-between gap-4 mt-6">
                <x-button
                        type="button"
                        size="large"
                        variant="rest"
                        wire:click="dispatch('close-modal')"
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
</div>
