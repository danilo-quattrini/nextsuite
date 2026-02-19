<?php

namespace App\Livewire;

use App\Domain\Skill\Services\SkillService;
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
        $this->loadSkills();
    }

    /**
     * Load skills with filters
     */
    public function loadSkills(): void
    {
        $this->skillsByCategory = $this->getSkillService()->groupByCategory();
    }

    /**
    * Create and configure skill service
    */
    public function getSkillService(): SkillService
    {
        $service = new SkillService();
        $service->loadSkillsForUser(Auth::user());

        if ($this->hideSoftSkills) {
            $service->filterByType('soft_skill');
        } elseif ($this->hideHardSkills) {
            $service->filter(fn($skill) => $skill->category?->type?->value === 'soft_skill');
        }

        return $service;
    }

    public function updatedSelectedSkillId(?int $skillId): void
    {
        if (!$skillId) {
            $this->showYearsInput = true;
            $this->skillYears = null;
            return;
        }

        $this->showYearsInput = !$this->getSkillService()->isSoftSkill($skillId);

        if (!$this->showYearsInput) {
            $this->skillYears = null;
        }
    }

    /**
     * Close the modal
     */
    #[On('close-modal')]
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Reset all form fields
     */
    private function resetForm(): void
    {
        $this->reset([
            'selectedSkillId',
            'skillLevel',
            'skillYears',
            'showYearsInput',
        ]);

        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.skill-modal');
    }
}
