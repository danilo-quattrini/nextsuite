<div>
    <x-button
            size="large"
            wire:click="$set('showAttributeModal', true)"
    >
        <x-heroicon name="plus" />
        New Attribute
    </x-button>

    @if($showAttributeModal)
        <x-popup-box modal="showAttributeModal">

            <x-slot:header>
                <x-authentication-card-logo/>
            </x-slot:header>

            <x-slot:subheader>
                {{ __('Add a new Attribute') }}
            </x-slot:subheader>

            <x-slot:message>
                {{ __('Here you can add a new attribute for the customer in base of your choice') }}
            </x-slot:message>

            {{--Categroy Choice--}}
            <x-form.container>
                <x-form.input-container>

                    <x-form.label-container label="Category" />

                    <x-form.select-wrapper :error="$errors->has('selectedCategoryId')">
                        <x-form.select-element wire:model.live="selectedCategoryId">
                            <x-slot:options>
                                <option value="" hidden> Select a category </option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </x-slot:options>
                        </x-form.select-element>
                    </x-form.select-wrapper>
                    <x-input-error for="selectedCategoryId" />
                </x-form.input-container>

                {{-- Attribute selection --}}

                @if(!empty($selectedCategoryId))
                    <x-form.input-container>

                        <x-form.label-container label="Attribute" />
                        <x-form.select-wrapper :error="$errors->has('selectedAttributeId')">
                            <x-form.select-element wire:model.live="selectedAttributeId">
                                <x-slot:options>
                                    <option value="" hidden>Select attribute</option>
                                    @foreach ($customerAttributes as $attributes)
                                        <option value="{{ $attributes->id }}">
                                            {{ $attributes->name }}
                                        </option>
                                    @endforeach
                                </x-slot:options>
                            </x-form.select-element>
                        </x-form.select-wrapper>
                        <x-input-error for="selectedAttributeId" />
                    </x-form.input-container>
                @endif

                {{-- Attribute selection --}}

                @if(!empty($selectedAttributeId))
                    <x-form.input-container>

                        <x-form.label-container label="{{ ucfirst($attribute->name) }}" />

                        @php($config = $this->attributeInputConfig())

                        @if($config['component'] === 'input' )
                            <x-input
                                    type="{{ $config['type'] }}"
                                    :error="$errors->has('value')"
                                    placeholder="{{ ucfirst($attribute->name) }}"
                                    wire:model.defer="value"
                                    value="{{ old('value') }}"
                            />
                        @elseif($config['component'] === 'select')
                            <x-form.select-wrapper>
                                <x-form.select-element wire:model.defer="value">
                                    <x-slot:options>
                                        <option value="" hidden>Select</option>

                                        @foreach ($config['options'] as $value => $label)
                                            <option value="{{ $value }}">
                                                {{ $label }}
                                            </option>
                                        @endforeach

                                    </x-slot:options>
                                </x-form.select-element>
                            </x-form.select-wrapper>
                        @elseif($config['component'] === 'radio')
                            <div class="flex gap-6">
                                @foreach($config['options'] as $value => $label)
                                    <x-radio-container>
                                        <x-slot:element>
                                            <input
                                                    type="radio"
                                                    value="{{ $value}}"
                                                    wire:model.defer="value"
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
                        @endif
                        <x-input-error for="value" />
                    </x-form.input-container>
                @endif
            </x-form.container>
            {{-- Actions --}}
            <div class="flex justify-between gap-4 mt-6">
                <x-button
                        type="button"
                        size="large"
                        variant="rest"
                        wire:click="$set('showAttributeModal', false)"
                >
                    Cancel
                </x-button>

                <x-button
                        type="button"
                        size="large"
                        wire:click="addAttribute"
                >
                    New Attribute
                </x-button>
            </div>

        </x-popup-box>

    @endif
</div>
