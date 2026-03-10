<x-card.card-container title="Hard Skills">
    {{--  COUNTER  --}}
    @if($this->hardSkills->count() > 0)
        <x-tag variant="white" size="md">
            {{ $this->hardSkills->count() }} {{ Str::plural('skill', $this->hardSkills->count()) }}
        </x-tag>
    @endif

    {{-- HARD SKILL MODAL --}}
    <x-slot:action>
        <x-button
                size="auto"
                wire:click="$dispatch('open-hard-skill-modal')"
        >
            <x-heroicon name="plus"/>
            New Skill
        </x-button>
    </x-slot:action>

    <livewire:skill-modal :user="$user"/>

    {{-- LOADING STATE --}}
    @if($isLoading)
        <div class="skills-loading">
            <x-spinner size="lg" />
            <span class="skills-loading__text">Updating skills...</span>
        </div>
    @endif

    {{-- EMPTY STATE FOR THE SECTION --}}
    @if($this->hardSkills->isEmpty())
        <x-empty-state
            icon="academic-cap"
            message="No hard skills added yet"
            description="Add technical skills and expertise to showcase capabilities"
        >
            <x-slot:action>
                <x-button
                        size="auto"
                        wire:click="$dispatch('open-hard-skill-modal')"
                >

                    <x-heroicon size="lg" name="plus"/>
                    Add Your First Skill
                </x-button>
            </x-slot:action>
        </x-empty-state>
    @else
        {{-- HARD SKILL GRID FOR CARDS --}}
        <div class="skills-grid">
            @foreach($this->visibleSkills as $skill)
                <x-card.skill-card
                        :skill="$skill"
                        wire:key="skill-{{ $skill->id }}"
                />
            @endforeach
        </div>

        {{-- SHOW MORE & LESS BUTTONS --}}
        @if($this->hasMore()  || $this->canShowLess())
            <div class="skills-pagination">
                @if($this->hasMore)
                    <div class="skills-pagination__info">
                        Showing {{ $this->visibleSkills->count() }} out of {{ $this->hardSkills->count() }} skills
                    </div>

                    <div class="skills-pagination__actions">
                        <x-button
                                variant="white"
                                size="auto"
                                wire:click="showMore"
                                wire:loading.attr="disabled"
                                wire:transition
                                @click.stop
                        >
                            Show {{ min($incrementBy, $this->remainingCount) }} More
                            <span class="skills-pagination__count">
                                    ({{ $this->remainingCount }} remaining)
                            </span>
                        </x-button>

                        <x-button
                            variant="white"
                            size="auto"
                            wire:click="showAll"
                            wire:loading.attr="disabled"
                        >
                            Show All
                        </x-button>
                    </div>
                @endif

                @if($this->canShowLess)
                    <x-button
                            variant="white"
                            size="auto"
                            wire:click="showLess"
                            wire:loading.attr="disabled"
                    >
                        Show less
                    </x-button>
                @endif
            </div>
       @endif
    @endif
</x-card.card-container>