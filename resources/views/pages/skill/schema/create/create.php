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
        $this->loadExistingSchema();
    }

    public function loadExistingSchema(): void
    {
        $existingSchema = $this->customer->skillSchema()->with('skill.category')->get();

        if ($existingSchema->isEmpty()) {
            return;
        }

        // Populate selected skills and their levels
        foreach ($existingSchema as $schemaEntry) {
            $this->selectedSkills[] = $schemaEntry->skill_id;
            $this->skillDefaultLevel[$schemaEntry->skill_id] = $schemaEntry->default_level;

            // Auto-select the category
            $categoryId = $schemaEntry->skill->category_id;
            if (!in_array($categoryId, $this->selectedCategory)) {
                $this->selectedCategory[] = $categoryId;
            }
        }
    }
    public function create(): void
    {

        $this->validate();


        $skillsData = [];
        foreach ($this->selectedSkills as $skillId) {
            $skillsData[] = [
                'skill_id' => $skillId,
                'default_level' => $this->skillDefaultLevel[$skillId] ?? 0,
            ];
        }

        // Use the hybrid approach with the service
        $schemaService = app(SkillSchemaService::class)
            ->for($this->customer, $skillsData);

        try {
            $schemaService->updateUserSchema();

            session()->flash('status', 'Skill schema created successfully!');

            $schemaService->applySchemaToAssignable(Auth::user(), true);

            $this->redirectRoute('customer.show', ['customer' => $this->customer->id]);

        } catch (\Exception $e) {
            session()->flash('warning', 'Failed to create skill schema: ' . $e->getMessage());
        }
    }

    /**
     * Update skill level when slider changes
     */
    public function updatedSkillDefaultLevel($value, $skillId): void
    {
        // Ensure the skill is selected
        if (!in_array($skillId, $this->selectedSkills)) {
            unset($this->skillDefaultLevel[$skillId]);
        }
    }

    /**
     * When a skill is selected/deselected
     */
    public function updatedSelectedSkills(): void
    {
        // Initialize default level for newly selected skills
        foreach ($this->selectedSkills as $skillId) {
            if (!isset($this->skillDefaultLevel[$skillId])) {
                $this->skillDefaultLevel[$skillId] = 0;
            }
        }

        // Remove levels for deselected skills
        $this->skillDefaultLevel = array_intersect_key(
            $this->skillDefaultLevel,
            array_flip($this->selectedSkills)
        );
    }


    /**
     * When a category is toggled
     */
    public function updatedSelectedCategory(): void
    {
        // Auto-deselect skills from deselected categories
        $selectedCategoryIds = $this->selectedCategory;

        $this->selectedSkills = array_values(array_filter(
            $this->selectedSkills,
            function ($skillId) use ($selectedCategoryIds) {
                $skill = Skill::find($skillId);
                return $skill && in_array($skill->category_id, $selectedCategoryIds);
            }
        ));

        // Clean up skill levels
        $this->updatedSelectedSkills();
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

    protected function rules(): array
    {
        return [
            'selectedSkills' => 'required|array|min:1',
            'selectedSkills.*' => 'exists:skills,id',
            'skillDefaultLevel.*' => 'integer'
        ];
    }
    protected function messages(): array
    {
        return [
            'selectedSkills.required' => 'You should select at least one skill for the schema.',
            'selectedSkills.min' => 'Please select at least one skill.',
            'skillDefaultLevel.min' => 'The :skill level should be at least 30'
        ];
    }
};
