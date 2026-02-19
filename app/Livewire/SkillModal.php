<?php

namespace App\Livewire;

use App\Domain\Skill\Services\SkillService;
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
    public bool $showModal = false;
    public bool $showYearsInput = true;
    public bool $hideSoftSkills = false;
    public bool $hideHardSkills = false;

    public function mount(): void
    {
        $this->authUser = Auth::user();
        $skillService = new SkillService();

        $skillService->loadSkillsForUser($this->authUser);

        $this->hideSkill($skillService);

        $this->skillsByCategory = $skillService->groupByCategory();
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

    public function hideSkill(SkillService $skillService): void
    {
        if($this->hideSoftSkills){
            $skillService->filterByType('soft_skill');
        }elseif ($this->hideHardSkills){
            $skillService->filter(fn ($skill) => $skill->category?->type?->value === 'soft_skill');
        }
    }

    public function render()
    {
        return view('livewire.skill-modal');
    }
}
