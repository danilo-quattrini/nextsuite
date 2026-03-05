<?php

use App\Domain\Skill\Services\SkillService;
use App\Domain\Skill\Services\SkillState\HardSkillState;
use App\Domain\Skill\Services\SkillState\SoftSkillState;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Role;

new #[Lazy]
class extends Component {
    public bool $showSection = false;
    public ?string $roleToSearch = '';
    public array $softSkills = [];
    public array $hardSkills = [];

    #[On('open-section')]
    public function openFilterSection(): void
    {
        $this->showSection = !$this->showSection;

        if (empty($this->hardSkills) && empty($this->softSkills)) {
            $skillService = new SkillService(null, new HardSkillState());
            $skillService->loadAllSkills();
            $this->hardSkills = $skillService->groupByCategory();
            $skillService->transitionToState(new SoftSkillState());
            $skillService->loadAllSkills();
            $this->softSkills = $skillService->groupByCategory();
        }
    }

    public function closeSection(): void
    {
        $this->showSection = false;
    }

    public function filter(): void
    {
        $this->dispatch('send-selected-skill', roleToSearch: $this->roleToSearch);
    }

    public function clearFilters(): void
    {
        $this->roleToSearch = '';
        $this->dispatch('clear-selected-skill');
        $this->dispatch('clear-rating');
        $this->dispatch('customer-filters-updated', roleToSearch: '', skillIds: [], ratingStars: 0);
    }
};
?>

<div class="my-4">
    @if($showSection)
        <x-card.card-container
                title="Filter"
                subtitle="Filter the customer in base of your preferences"
                class="text-wrap"
                wire:transition="filterSection"
        >
            {{--      CLOSE BUTTON      --}}
            <x-slot:action>
                <button
                        class="cursor-pointer hover:text-secondary-error transition-colors delay-50 duration-150 ease-in-out"
                        wire:click="closeSection"
                >
                    <x-heroicon name="x-circle" size="lg"/>
                </button>
            </x-slot:action>

            {{--      SEARCH ROLE SECTION      --}}
            <livewire:filters.search-role wire:model.live="roleToSearch" />

            {{--      HARD SKILL SECTION      --}}
            <x-filters.filter-subsection
                    title="Hard Skill"
            >
                <livewire:filters.base-filter :skills="$hardSkills"/>
            </x-filters.filter-subsection>

            {{--      SOFT SKILL SECTION      --}}
            <x-filters.filter-subsection
                    title="Soft Skill"
            >
                <livewire:filters.base-filter :skills="$softSkills"/>
            </x-filters.filter-subsection>

            {{--      REVIEW SECTION BUTTON      --}}
            <x-filters.filter-subsection
                    title="Rating"
            >
                <livewire:rating-stars size="xl" selectable="true"/>
            </x-filters.filter-subsection>

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