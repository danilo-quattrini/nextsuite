<?php

namespace App\Livewire\Skill;

use App\Domain\Skill\Contracts\SkillAssignable;
use App\Domain\Skill\Services\SkillAssignmentService;
use App\Domain\Skill\Services\SkillService;
use App\Domain\Skill\Services\SkillState\HardSkillState;
use App\Livewire\Traits\HasPaginatedCollection;
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
    use HasPaginatedCollection;
    public ?SkillAssignable $user = null;

    public bool $isLoading = false;

    #[Computed]
    public function hardSkills(): Collection
    {
        return $this->getSkillService()
            ->loadSkillFromAssignable()
            ->getSkills();
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
     * Remove a skill from the user
     */
    public function removeSkill(int $skillId): void
    {

        try {
            $this->user->skills()->detach($skillId);

            unset($this->hardSkills);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Skill removed successfully!'
            ]);

        } catch (Exception $e) {
            Log::error('Failed to remove hard skill', [
                'user_id' => $this->user->id,
                'skill_id' => $skillId,
                'error' => $e->getMessage()
            ]);

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Failed to remove skill.'
            ]);
        }
    }

    /**
     * Update skill level or years
     * TODO
     */

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

    protected function getPaginatedCollection(): Collection
    {
        return $this->hardSkills;
    }

    public function render()
    {
        return view('livewire.skill.hard-skills');
    }
}
