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
        @if(count($this->hardSkills) > $visibleCount)
            <button
                    wire:click.preserve-scroll="showMore"
                    wire:transition
                    @click.stop
                    class="text-xs text-primary-grey hover:text-primary cursor-pointer transition-colors mt-1 px-2"
            >
                Show more ({{ count($this->hardSkills) - $visibleCount }} remaining)
            </button>
        @elseif(count($this->hardSkills) !== 1 && $hardSkills->isNotEmpty())
            <button
                    wire:click.preserve-scroll="showLess"
                    wire:transition
                    @click.stop
                    class="text-xs text-primary-grey hover:text-primary cursor-pointer transition-colors mt-1 px-2"
            >
                Show less
            </button>
        @endif
    @endif
</x-card.card-container>