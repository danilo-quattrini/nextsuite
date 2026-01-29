<div>

    {{-- BUTTON TO ADD A NEW SKILL  --}}
    <x-button
            size="auto"
            wire:click="$set('showSkillModal', true)"
    >
        <x-heroicon name="plus" />
        New Skill
    </x-button>

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
                        <x-input type="number" min="0" wire:model.live="skillYears" placeholder="Years" right-icon="clock"  :error="$errors->has('skillYears')" />
                        <x-input-error for="skillYears"/>
                    </x-form.input-container>
                @endif
                    {{-- Level --}}
                    <x-form.input-container size="auto">
                        <x-form.label-container label="Level" :required="true"/>
                        <div
                            class="relative w-full"
                            x-data="{ level: @entangle('skillLevel').live, min: 0, max: 100 }"
                        >
                            <span
                                class="absolute top-0 px-3 py-1 text-sm font-medium text-white bg-primary rounded-md whitespace-nowrap pointer-events-none"
                                x-text="level ?? 0"
                                :style="`left: calc(${((level ?? 0) - min) / (max - min) * 100}%); transform: translate(-50%, -120%);`"
                            ></span>
                            <input
                                class="slider"
                                type="range"
                                min="0"
                                max="100"
                                value="1"
                                x-model="level"
                                wire:model.live="skillLevel"
                                :error="$errors->has('skillLevel')"
                            />
                        </div>
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
</div>
