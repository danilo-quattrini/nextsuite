<form wire:submit.prevent="submit">
    <x-form.container>
        @if($step === 1)

            {{-- TEMPLATE NAME  --}}
            <x-form.input-container>
                <x-form.label-container label="Template Name" :required="true"/>

                <x-input
                        wire:model.defer="name"
                        placeholder="Enter the name.."
                        right-icon="document-text"
                        :error="$errors->has('name')"
                        value="{{ old('name') }}"
                />

                <x-input-error for="name"/>
            </x-form.input-container>

            {{-- TEMPLATE TYPE  --}}
            <x-form.input-container>
                <x-form.label-container label="Format" :required="true"/>

                <div class="flex gap-6">
                    @foreach ($templateType as $value => $label)
                        <x-radio-container>
                            <x-slot:element>
                                <input
                                        type="radio"
                                        value="{{ $label }}"
                                        wire:model.defer="type"
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
                <x-input-error for="type"/>
            </x-form.input-container>

            <x-button size="large" wire:click="nextStep">
                Next
            </x-button>

        @elseif($step === 2)

            {{-- TEMPLATE CATEGORY  --}}
            <x-form.input-container>
                <x-form.label-container label="Category" :required="true"/>

                <x-form.select-wrapper :error="$errors->has('category')">
                    <x-form.select-element wire:model.live="category">
                        <x-slot:options>
                            <option value="" selected hidden> Select Category </option>
                            @foreach ($templateCategory as $key => $value)
                                <option value="{{ $value }}">
                                    {{ ucfirst($value) }}
                                </option>
                            @endforeach
                        </x-slot:options>
                    </x-form.select-element>
                </x-form.select-wrapper>

                <x-input-error for="category"/>
            </x-form.input-container>

            {{-- TEMPLATE SETTING --}}
            @foreach($settings as $key => $value)
                <x-form.input-container>
                    <x-form.label-container
                            label="{{ Str::headline($key) }}"
                            :required="true"
                    />

                    <x-input
                            wire:model.defer="settings.{{ $key }}"
                            placeholder="Enter the . {{ Str::headline($key) }}"
                            right-icon="document-text"
                            :error="$errors->has('settings'. $key)"
                            value="{{ $value }}"
                    />

                    <x-input-error for="settings.{{ $key }}"/>
                </x-form.input-container>
            @endforeach

        @endif
    </x-form.container>
</form>