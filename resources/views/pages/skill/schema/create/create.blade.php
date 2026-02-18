@php use Carbon\Carbon; @endphp
<x-card.content-page-card
        title="Create Skill"
        description="Choose the skills you want to define to the customer."
        :has-counter="false"
        :has-grid="false"
>
    <div class="page-content__card">
        <div class="user-view">
            <livewire:user.info-header :user="$customer"/>
            <form wire:submit.prevent="create" enctype="multipart/form-data">
                @csrf
                <x-form.container>
                    <div class="grid lg:grid-cols-4 md:grid-cols-2 items-center gap-6">
                        {{-- CATEGORIES CARD --}}
                        @foreach($categories as $category)
                            <x-card.card-container card-size="lg" size="2xl" :title="$category['name']">
                                <x-slot:action>
                                    <x-form.toggle-switch
                                            id="category-{{ $category['id'] }}"
                                            name="category-{{ $category['name'] }}"
                                            value="{{ $category['id'] }}"
                                            wire:model.live="selectedCategory"
                                    />
                                </x-slot:action>

                                {{-- SKILL SELECTION (Dependent on Category) --}}
                                @php
                                    $isCategorySelected = $this->isCategorySelected($category['id']);
                                @endphp

                                @foreach($category['skills'] as $skill)
                                    @php
                                        $isSkillSelected = $this->isSkillSelected($skill['id']);
                                    @endphp
                                    <div class="flex flex-wrap gap-8 {{ !$isCategorySelected ? 'opacity-50 pointer-events-none' : '' }}"
                                         wire:transition>
                                        @if($isSkillSelected)
                                            <x-form.slider
                                                    id="skill-{{$skill['value']}}-level"
                                                    name="skill-{{$skill['value']}}-level"
                                                    wire:model.live="skillDefaultLevel.{{ $skill['id'] }}"
                                            />
                                            <x-input-error for="skillDefaultLevel.{{ $skill['id'] }}"/>
                                        @endif
                                        <x-toggle-container
                                                wire:key="{{ $skill['id'] }}"
                                                description="{{ $skill['description'] }}"
                                        >
                                            <x-slot:element>
                                                <x-form.checkbox
                                                        id="skill-{{ $skill['id'] }}"
                                                        name="skill-{{ $skill['label'] }}"
                                                        value="{{ $skill['id'] }}"
                                                        wire:model.live="selectedSkills"
                                                        :disabled="!$isCategorySelected"
                                                />
                                            </x-slot:element>

                                            <x-slot:span>
                                                <span class="ds-checkbox-mark"></span>
                                            </x-slot:span>
                                            {{ $skill['label'] }}
                                        </x-toggle-container>
                                    </div>
                                @endforeach
                            </x-card.card-container>
                        @endforeach
                    </div>

                    <x-input-error for="selectedSkills"/>

                    <div class="flex lg:justify-end md:justify-center">
                        <x-button size="large" type="submit">
                            Create
                        </x-button>
                    </div>
                </x-form.container>
            </form>
        </div>
    </div>
</x-card.content-page-card>