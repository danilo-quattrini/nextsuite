<?php

use App\Domain\Skill\Services\SkillService;
use App\Domain\Skill\Services\SkillState\HardSkillState;
use App\Domain\Skill\Services\SkillState\SoftSkillState;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Component;

new #[Lazy]
class extends Component {
    public bool $showSection = false;
    public array $softSkills = [];
    public array $hardSkills = [];

    #[On('open-section')]
    public function openFilterSection(): void
    {
        $this->showSection = true;

        if (empty($this->hardSkills) && empty($this->softSkills)) {
            $skillService = new SkillService(null, new HardSkillState());
            $skillService->loadAllSkills();
            $this->hardSkills = $skillService->groupByCategory();
            $skillService->transitionToState(new SoftSkillState());
            $skillService->loadAllSkills();
            $this->softSkills = $skillService->groupByCategory();
        }
    }

    #[On('close-section')]
    public function closeSection(): void
    {
        $this->showSection = false;
    }

    public function filter()
    {
        $this->dispatch('send-selected-skill');
    }

    public function clearFilters(): void
    {
        $this->dispatch('clear-selected-skill');
        $this->dispatch('clear-rating');
        $this->dispatch('customer-filters-updated', skillIds: [], ratingStars: 0);
    }
};
?>

<div class="my-4">
    @if($showSection)
        <x-card.card-container
                title="Filter"
                subtitle="Filter the customer in base of your preferences"
                class="text-wrap"
                wire:transition
        >
            {{--      CLOSE BUTTON      --}}
            <x-slot:action>
                <button
                        class="cursor-pointer hover:text-secondary-error transition-colors delay-50 duration-150 ease-in-out"
                        wire:click="closeSection"
                >
                    <x-heroicon name="x-circle" size="xl"/>
                </button>
            </x-slot:action>

            {{--      HARD SKILL SECTION      --}}
            <div class="space-y-2 border-b border-outline-grey/60 pb-3">
                <p class="font-semibold uppercase tracking-wideborder-b-black">
                    Hard Skill
                </p>
                <livewire:filters.base-filter :skills="$hardSkills"/>
            </div>

            {{--      SOFT SKILL SECTION      --}}
            <div class="space-y-2 border-b border-outline-grey/60 pb-3">
                <p class="font-semibold uppercase tracking-wideborder-b-black">
                    Soft Skill
                </p>
                <livewire:filters.base-filter :skills="$softSkills"/>
            </div>

            {{--      REVIEW SECTION BUTTON      --}}
            <div class="space-y-2 border-b border-outline-grey/60 pb-3">
                <p class="text-xs uppercase tracking-wide text-primary-grey">
                    Rating
                </p>
                <livewire:rating-stars size="xl" selectable="true"/>
            </div>

            {{--      BUTTON SECTION      --}}
            <div class="flex justify-between items-center my-2">
                <x-button
                        variant="outline-error"
                        size="auto"
                        wire:click="clearFilters"
                >
                    <x-heroicon name="trash"/>
                    Clear
                </x-button>
                <x-button
                        size="auto"
                        wire:click="filter"
                >
                    <x-heroicon name="magnifying-glass"/>
                    Find
                </x-button>
            </div>
        </x-card.card-container>
    @endif
</div>