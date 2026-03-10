<?php

use App\Domain\Skill\Services\SkillAssignmentService;
use App\Domain\Skill\Services\SkillService;
use App\Domain\Skill\Services\SkillState\HardSkillState;
use App\Models\Customer;
use App\Models\Skill;
use Illuminate\Support\Collection;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Component;

new #[Lazy]
class extends Component {
    public ?Customer $customer = null;
    public ?Collection $hardSkills = null;
    public int $visibleSection = 6;

    public function mount(): void
    {
        $skillService = new SkillService($this->customer, new HardSkillState());
        $this->hardSkills = $skillService->loadSkillFromAssignable()->getSkills();
    }

    public function showMore(): void
    {
        $this->visibleSection += count($this->hardSkills) - $this->visibleSection;
        $this->updateHardSkillModel();
    }

    public function showLess(): void
    {
        $this->visibleSection = 2;
        $this->updateHardSkillModel();
    }

    #[On('skill-added')]
    public function addSkillToCustomer(int $skillId, int $skillLevel, int|null $skillYears): void
    {
        $skill = Skill::find($skillId);
        if (!$skill || $skill->isSoftSkill()) {
            return;
        }

        try {
            $user = Auth::user();
            app(SkillAssignmentService::class)->assign(
                $this->customer,
                $user,
                $skillId,
                $skillLevel,
                $skillYears
            );
            $this->updateHardSkillModel();

            session()->flash('status', 'Skill added successfully!');
        } catch (Exception $e) {
            session()->flash('error', 'Failed to add skill: '.$e->getMessage());
        }
    }

    public function updateHardSkillModel(): void
    {
        $skillService = new SkillService($this->customer, new HardSkillState());
        $this->hardSkills = $skillService->loadSkillFromAssignable()->getSkills();
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
};
