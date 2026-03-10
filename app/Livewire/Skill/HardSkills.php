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

    #[Computed]
    public function hardSkills(): Collection
    {
        return $this->getSkillService()
            ->loadSkillFromAssignable()
            ->getSkills();
    }

    /**
     * Get all the visible skill that has been declared in visibleCount
     */
    #[Computed]
    public function visibleSkills(): Collection
    {
        return $this->hardSkills->take($this->visibleCount);
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
                    <div class="flex items-center justify-center py-12">
                        <x-spinner size="lg" label="Loading hard skills..." />
                    </div>
                </x-card.card-container>
        HTML;
    }

    public function render()
    {
        return view('livewire.skill.hard-skills');
    }
}
