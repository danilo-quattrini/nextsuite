<?php

namespace App\Livewire;

use App\Domain\Skill\Services\SkillService;
use App\Models\Skill;
use App\Models\User;
use App\Traits\WithSkill;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class SkillModal extends Component
{
    use WithSkill;
    public ?int $selectedSkillId = null;
    public ?int $skillLevel = 1;
    public ?int $skillYears = null;
    public array $skillsByCategory = [];

    public ?User $authUser = null;

    public ?int $selectedCategoryId = null;
    public bool $showModal = false;
    public bool $showYearsInput = true;
    public bool $hideSoftSkills = false;
    public bool $hideHardSkills = false;

    /**
     * Validation rules
     */
    protected function rules(): array
    {
        return [
            'selectedSkillId' => ['required', 'exists:skills,id'],
            'skillLevel' => ['required', 'integer', 'min:1', 'max:100'],
            'skillYears' => ['nullable', 'integer', 'min:0', 'max:30'],
        ];
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return [
            'selectedSkillId.required' => 'Please select a skill.',
            'skillLevel.required' => 'Please set a skill level.',
            'skillLevel.between' => 'Skill level must be between 1 and 100',
            'skillYears.max' => 'Years of experience cannot exceed 30.',
        ];
    }
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

    #[On('close-modal')]
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }
}
