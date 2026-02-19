<?php

namespace App\Livewire;

use App\Models\Skill;
use App\Models\User;
use App\Traits\WithSkill;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SkillModal extends Component
{
    use WithSkill;

    public ?User $authUser = null;

    public ?int $selectedCategoryId = null;
    public bool $showSkillModal = false;
    public bool $showYearsInput = true;
    public bool $hideSoftSkills = false;
    public bool $hideFieldSkills = false;

    public function mount(): void
    {
        $user = auth()->user();
        $this->authUser = Auth::user();

        if ($user->company) {
            $user->company->load('fields.categories.skills');
            $skills = $user->company->fields
                ->flatMap(fn ($field) => $field->categories)
                ->flatMap(fn ($category) => $category->skills)
                ->unique('id');
        } else {
            $skills = Skill::with('category')->get();
        }

        $skills = $this->hideSkill($skills);

        $this->skillsByCategory = $skills
            ->groupBy(fn ($skill) => $skill->category->name)
            ->toArray();
    }

    public function toggleYearsInput($skillId): bool
    {
        $categoryType = Skill::findOrFail($skillId)->category->type->value;
        return $categoryType !== 'soft_skill';
    }

    public function updatedSelectedSkillId($skillId): void
    {
        $this->selectedSkillId = $skillId;
        $this->showYearsInput = $this->toggleYearsInput($skillId);
    }

    public function hideSkill(Collection $skills): Collection
    {
        return match (true){
            $this->hideFieldSkills =>  $skills->reject(
                fn ($skill) => $skill->category?->type?->value !== 'soft_skill'
            ),
            $this->hideSoftSkills => $skills->reject(
                fn ($skill) => $skill->category?->type?->value === 'soft_skill'
            )
        };
    }

    public function render()
    {
        return view('livewire.skill-modal');
    }
}
