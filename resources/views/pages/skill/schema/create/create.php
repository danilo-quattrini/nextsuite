<?php

use App\Domain\Skill\Services\SkillSchemaService;
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
    public ?array $selectedSkills = [];
    public ?array $skillDefaultLevel = [];

    public function mount(): void
    {
        $this->categories = $this->computeCategories();
    }

    public function create(): void
    {
        // TODO: ADD VALIDATION
        $user = Auth::user();

        $user->skillSchema()->syncWithoutDetaching($this->selectedSkill);
    }


    public function isCategorySelected($categoryId): bool
    {
        return in_array($categoryId, $this->selectedCategory);
    }

    public function isSkillSelected($skillId): bool
    {
        return in_array($skillId, $this->selectedSkills);
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
                    'skills' => $this->computeSkills($category->skills)
                ];
            });
    }

    public function computeSkills(
        Collection $skill
    ): array
    {
        return $skill->map(function ($skill) {
                return [
                    'id' => $skill->id,
                    'label' => $skill->name,
                    'description' => $skill->description ?? '',
                    'value' => strtolower(str_replace(" ", "-", $skill->name))
                ];
        })->toArray();
    }
};
