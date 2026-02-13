<?php

use App\Models\Customer;
use Livewire\Component;
use App\Models\Category;
use App\Models\Skill;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public ?Customer $customer = null;
    public ?Collection $categories = null;
    public ?Collection $skills = null;

    public $selectedCategory = null;
    public ?array $selectedSkill = null;

    public function mount(): void
    {
        $this->categories = Category::where('type', 'soft_skill')
            ->where('name', '<>', 'Abilities')
            ->get();

        $this->skills = collect();
    }

    public function updatedSelectedCategory($categoryId): void
    {
        if (!empty($categoryId)) {
            $this->skills = Skill::where('category_id', $categoryId)
                ->get()
                ->map(function ($skill) {
                    return [
                        'id' => $skill->id,
                        'value' => strtolower(str_replace(" ", "-", $skill->name)),
                        'label' => $skill->name
                    ];
                });
        } else {
            $this->skills = collect();
        }
        $this->selectedSkill = [];
    }

    public function create(): void
    {
        $user = Auth::user();

        $user->skillSchema()->syncWithoutDetaching($this->selectedSkill);
    }
};
?>
@php use Carbon\Carbon; @endphp
<x-card.content-page-card
        title="Create Skill"
        description="Choose the skills you want to define to the customer."
        :has-counter="false"
        :has-grid="false"
>

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
                                    <x-form.select-element name="category" id="category"
                                                           wire:model.live="selectedCategory">
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
                                                            id="skill-{{ $skill['id'] }}"
                                                            name="skill-{{ $skill['label'] }}"
                                                            value="{{ $skill['id'] }}"
                                                            wire:model.live="selectedSkill"
                                                    />
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
                            <x-button size="large" type="submit">
                                Create
                            </x-button>
                        </div>

                    </x-form.container>
                </form>
            </x-form.container>
        </div>
</x-card.content-page-card>

