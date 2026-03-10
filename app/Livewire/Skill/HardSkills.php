<?php

namespace App\Livewire\Skill;

use App\Domain\Skill\Contracts\SkillAssignable;
use App\Domain\Skill\Services\SkillAssignmentService;
use App\Domain\Skill\Services\SkillService;
use App\Domain\Skill\Services\SkillState\HardSkillState;
use App\Models\Skill;
use Auth;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Component;

#[Lazy]
class HardSkills extends Component
{
    public ?SkillAssignable $user = null;

    public int $visibleCount = 6;
    public int $initialVisible = 6;
    public int $incrementBy = 6;

    public bool $isLoading = false;

    #[Computed]
    public function hardSkills(): Collection
    {
        return $this->getSkillService()
            ->loadSkillFromAssignable()
            ->getSkills();
    }

    // ======== HELPER METHODS ==========
    /**
     * Take a certain number of items in the collection of hardSkills
     * @return Collection
     */
    #[Computed]
    public function visibleSkills(): Collection
    {
        return $this->hardSkills->take($this->visibleCount);
    }

    // ======== PAGINATION METHODS ==========
    /**
     * Check if there is more skill in the collection
     * @return bool
     */
    #[Computed]
    public function hasMore(): bool
    {
        return $this->hardSkills->count() > $this->visibleCount;
    }

    /**
     * Counter to show how many hardSkill remain to show
     * @return int
     */
    #[Computed]
    public function remainingCount(): int
    {
        return max(0, $this->hardSkills->count() - $this->visibleCount);
    }

    /**
     * Check if it can hide all the skill, that means it has been show all the hardSkill
     * @return bool
     */
    #[Computed]
    public function canShowLess(): bool
    {
        return $this->visibleCount > $this->initialVisible
            && $this->hardSkills->count() > 1;
    }

    /**
     * Show more skills
     */
    public function showMore(): void
    {
        $remaining = $this->remainingCount;

        if ($remaining > 0) {
            $this->visibleCount += min($this->incrementBy, $remaining);
        }
    }

    /**
     * Show fewer skills
     */
    public function showLess(): void
    {
        $this->visibleCount = $this->initialVisible;
    }

    /**
     * Show all skills at once
     */
    public function showAll(): void
    {
        $this->visibleCount = $this->hardSkills->count();
    }

    // ======== SKILL OPERATINO METHODS ==========
    /**
     * Handle skill addition from modal
     */
    #[On('skill-added')]
    public function addSkillToUser(
        int $skillId,
        int $skillLevel,
        ?int $skillYears = null
    ): void {
        $this->isLoading = true;

        try {
            $skill = Skill::findOrFail($skillId);

            // Validate skill type
            if ($skill->isSoftSkill()) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Cannot add soft skill to hard skills section.'
                ]);
                return;
            }

            // Check if skill already exists
            if ($this->user->skills()->where('skill_id', $skillId)->exists()) {

                $this->dispatch('notify', [
                    'type' => 'warning',
                    'message' => 'This skill has already been added.'
                ]);
                return;
            }

            // Assign the skill
            app(SkillAssignmentService::class)->assign(
                model: $this->user,
                evaluator: Auth::user(),
                skillId: $skillId,
                skillLevel: $skillLevel,
                skillYears: $skillYears
            );

            // Refresh the computed property
            unset($this->hardSkills);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "Successfully added {$skill->name}!"
            ]);

            session()->flash('status', 'Skill added successfully!');

        } catch (Exception $e) {
            Log::error('Failed to add hard skill to user', [
                'user_id' => $this->user->id,
                'skill_id' => $skillId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Failed to add skill. Please try again.'
            ]);

            session()->flash('error', 'Failed to add skill: '.$e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Get skill service instance
     */
    private function getSkillService(): SkillService
    {
        return new SkillService($this->user, new HardSkillState());
    }

    public function placeholder(): string
    {
        return <<<'HTML'
                <x-card.card-container title="Hard Skills">
                    <div class="skills-loading">
                        <x-spinner size="lg" />
                        <span class="skills-loading__text">Updating skills...</span>
                    </div>
                </x-card.card-container>
        HTML;
    }

    public function render()
    {
        return view('livewire.skill.hard-skills');
    }
}
