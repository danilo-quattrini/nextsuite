<?php

namespace App\Livewire;

use App\Models\Skill;
use App\Traits\WithSkill;
use Livewire\Component;

class SkillModal extends Component
{
    use WithSkill;
    public ?int $selectedCategoryId = null;
    public bool $showSkillModal = false;

    public function mount(): void
    {
        $user = auth()->user();

        if ($user->company) {
            $user->company->load('fields.categories.skills');

            $this->skillsByCategory = $user->company->fields
                ->flatMap(fn ($field) => $field->categories)
                ->flatMap(fn ($category) => $category->skills)
                ->unique('id')
                ->groupBy(fn ($skill) => $skill->category->name)
                ->toArray();
        } else {
            $this->skillsByCategory = Skill::with('category')
                ->get()
                ->groupBy(fn ($skill) => $skill->category->name)
                ->toArray();
        };
    }

    public function updatedSelectedSkillId($skillId): void
    {
        $this->selectedSkillId = $skillId;
    }

    public function render()
    {
        return view('livewire.skill-modal');
    }
}
