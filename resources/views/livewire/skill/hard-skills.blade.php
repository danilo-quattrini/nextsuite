<x-card.card-container title="Hard Skills">
    {{--  COUNTER  --}}
    @if($this->hardSkills->count() > 0)
        <x-tag variant="white">
            {{ $this->hardSkills->count() }} {{ Str::plural('skill', $this->hardSkills->count()) }}
        </x-tag>
    @endif

    @if($this->hardSkills()->isNotEmpty())
        {{-- HARD SKILL MODAL --}}
        <x-slot:action>
            <x-button
                    size="icon"
                    wire:click="$dispatch('open-hard-skill-modal')"
            >
                <x-heroicon size="lg" name="plus"/>
            </x-button>
        </x-slot:action>
    @endif

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
            @foreach($this->visibleItems as $skill)
                <x-card.skill-card
                        :skill="$skill"
                        wire:key="skill-{{ $skill->id }}"
                        :user="$user"
                        :editable="true"
                />
            @endforeach
        </div>

        {{-- SHOW MORE & LESS BUTTONS --}}
        @if($this->hasMore()  || $this->canShowLess())
            <div class="skills-pagination">
                @if($this->hasMore)
                    <div class="skills-pagination__info">
                        Showing {{ $this->visibleItems->count() }} out of {{ $this->hardSkills->count() }} skills
                    </div>

                    <div class="skills-pagination__actions">
                        <x-button
                                variant="white"
                                wire:click="showMore"
                                wire:loading.attr="disabled"
                                wire:transition
                        >
                            Show {{ min($incrementBy, $this->remainingCount) }} More
                            <span class="skills-pagination__count">
                                    ({{ $this->remainingCount }} remaining)
                            </span>
                        </x-button>

                        <x-button
                            variant="white"
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