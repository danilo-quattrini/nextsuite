<?php

namespace App\Livewire;

use App\Domain\Skill\Contracts\SkillAssignable;
use App\Domain\Skill\Services\SkillService;
use App\Domain\Skill\Services\SkillState\HardSkillState;
use App\Domain\Skill\Services\SkillState\SoftSkillState;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Component;

#[Lazy]
class SkillModal extends Component
{
    public ?SkillAssignable $user = null;

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
            'skillLevel' => ['required', 'integer', 'min:25', 'max:100'],
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
            'skillLevel.between' => 'Skill level must be between 25 and 100',
            'skillYears.max' => 'Years of experience cannot exceed 30.',
        ];
    }

    #[On('open-hard-skill-modal')]
    public function openHardSkillModal(): void
    {
        if ($this->hideHardSkills) {
            $this->hideHardSkills = false;
        }

        $this->hideSoftSkills = true;
        $this->openModal();
    }

    #[On('open-soft-skill-modal')]
    public function openSoftSkillModal(): void
    {
        if ($this->hideSoftSkills) {
            $this->hideSoftSkills = false;
        }

        $this->hideHardSkills = true;
        $this->openModal();
    }

    public function openModal(): void
    {
        $this->showModal = true;
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
        if ($this->hideSoftSkills) {
            $service = new SkillService($this->user, new HardSkillState());
            $service->loadAllSkills();
        } elseif ($this->hideHardSkills) {
            $service = new SkillService($this->user, new SoftSkillState());
            $service->loadSkillsForUser(Auth::user());
        }else{
            $service = new SkillService($this->user);
            $service->loadAllSkills();
        }

        return $service;
    }


    /**
     * When skill selection changes
     */
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
     * Add skill and notify parent component
     */
    public function addSkill(): void
    {

        $this->validate();

        try {

            $this->dispatch(
                'skill-added',
                skillId: $this->selectedSkillId,
                skillLevel: $this->skillLevel,
                skillYears: $this->skillYears
            );

            $this->closeModal();

            $this->dispatch('notify', [
                'type' => 'status',
                'message' => 'Skill added successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to add skill', [
                'skill_id' => $this->selectedSkillId,
                'error' => $e->getMessage()
            ]);

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Failed to add skill. Please try again.'
            ]);
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
