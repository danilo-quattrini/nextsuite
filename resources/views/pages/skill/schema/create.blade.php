<?php

use App\Models\Customer;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\Category;
use App\Models\Skill;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public ?Customer $customer = null;
    public ?Collection $categories = null;

    public ?array $selectedCategory = [];
    public ?array $selectedSkill = [];

    public function mount(): void
    {
        $this->categories = $this->computeCategories();
    }

    public function create(): void
    {
        $user = Auth::user();

        $user->skillSchema()->syncWithoutDetaching($this->selectedSkill);
    }

    #[Computed]
    public function computeSkills($categoryId): array
    {
        return Skill::where('category_id', $categoryId)
            ->get()
            ->map(function ($skill) {
                return [
                    'id' => $skill->id,
                    'label' => $skill->name,
                    'value' => strtolower(str_replace(" ", "-", $skill->name))
                ];
            })->toArray();

    }

    #[Computed]
    public function computeCategories(): Collection
    {
        return Category::where('type', 'soft_skill')
            ->where('name', '<>', 'Abilities')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'skills' => $this->computeSkills($category->id)
                ];
            });
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
    <div class="page-content__card">
        <div class="user-view">
            <livewire:user.info-header :user="$customer"/>
            <form wire:submit.prevent="create" enctype="multipart/form-data">
                @csrf
                <x-form.container>
                    <div class="grid lg:grid-cols-4 md:grid-cols-2 items-center gap-6">

                        {{-- CATEGORIES CARD --}}
                        @foreach($categories as $category)
                            <x-card.card-container size="2xl" :title="$category['name']">
                                <x-slot:action>
                                    <x-toggle-container>
                                        <x-slot:element>
                                            <x-checkbox
                                                    id="category-{{ $category['id'] }}"
                                                    name="category-{{ $category['name'] }}"
                                                    value="{{ $category['id'] }}"
                                                    :wrap="false"
                                                    wire:model.live="selectedCategory"
                                            />
                                        </x-slot:element>
                                        <x-slot:span>
                                            <span class="ds-checkbox-mark"/>
                                        </x-slot:span>
                                    </x-toggle-container>
                                </x-slot:action>

                                {{-- SKILL SELECTION (Dependent on Category) --}}
                                @foreach($category['skills'] as $skill)
                                    <div class="flex flex-wrap gap-6">
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
                                    </div>
                                @endforeach
                            </x-card.card-container>
                        @endforeach
                    </div>

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

