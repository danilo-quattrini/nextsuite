@php use Carbon\Carbon; @endphp
<div class="page-content__container">

    {{--  Content  --}}
    <div class="page-content__hero">
        <div class="page-content__hero-inner">
            <div class="page-content__hero-row">
                <div class="page-content__hero-copy">
                    <h2 class="page-content__title">
                        {{ __('Create Skill ') }}
                    </h2>
                    <p class="page-content__subtitle">
                        {{__('Create a new skill to assign to a customer')}}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content__body">
        {{--  Form  --}}
        <div class="page-content__card">
            <x-form.container>
                <form wire:submit.prevent="create" enctype="multipart/form-data">
                    @csrf
                    <x-form.container>
                        <div class="grid lg:grid-cols-2 md:grid-cols-1 items-center gap-6">
                            {{-- FULL NAME --}}
                            <x-form.input-container size="auto">
                                <x-form.label-container label="Category" :required="true"/>

                                <x-form.select-wrapper :error="$errors->has('selectedCategory')">
                                    <x-form.select-element name="category" id="category" wire:model.live="selectedCategory">
                                        <x-slot:options>
                                            <option value="" hidden>
                                                Select category
                                            </option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}">
                                                    {{ $cat->name}}
                                                </option>
                                            @endforeach
                                        </x-slot:options>
                                    </x-form.select-element>
                                </x-form.select-wrapper>

                                <x-input-error for="selectedCategory"/>
                            </x-form.input-container>

                            @if($selectedCategory)
                                {{-- SKILL SELECTION (Dependent on Category) --}}
                                <x-form.input-container size="auto">
                                    <x-form.label-container label="Skill" :required="true"/>

                                    <div class="flex flex-wrap gap-6">
                                        @foreach($skills as $skill)
                                            <x-toggle-container wire:key="{{ $skill['id'] }}">
                                                <x-slot:element>
                                                    <x-checkbox
                                                            id="skill"
                                                            name="skill"
                                                            wire:model.live="selectedSkill"/>
                                                </x-slot:element>

                                                <x-slot:span>
                                                    <span class="ds-checkbox-mark"></span>
                                                </x-slot:span>
                                                {{ $skill['label'] }}
                                            </x-toggle-container>
                                        @endforeach
                                    </div>
                                    <x-input-error for="selectedSkill"/>
                                </x-form.input-container>
                            @endif
                        </div>

                        <div class="flex lg:justify-end md:justify-center">
                            <x-button size="large">
                                Create
                            </x-button>
                        </div>

                    </x-form.container>
                </form>
            </x-form.container>
        </div>
    </div>

</div>

