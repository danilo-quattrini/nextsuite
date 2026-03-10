<?php

namespace App\Livewire\Skill;

use App\Domain\Skill\Contracts\SkillAssignable;
use App\Domain\Skill\Services\SkillAssignmentService;
use App\Domain\Skill\Services\SkillService;
use App\Domain\Skill\Services\SkillState\HardSkillState;
use App\Models\Customer;
use App\Models\Skill;
use Auth;
use Exception;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class HardSkills extends Component
{
    public ?SkillAssignable $user = null;

    public int $visibleCount = 6;
    public int $initialVisible = 6;
    public int $incrementBy = 6;

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
