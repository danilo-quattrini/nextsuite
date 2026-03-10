<x-card.card-container title="Hard Skills">
    {{--  COUNTER  --}}
    @if($hardSkills->count() > 0)
        <x-tag variant="white" size="lg">
            {{ $hardSkills->count() }} {{ Str::plural('skill', $hardSkills->count()) }}
        </x-tag>
    @endif

    <x-slot:action>
        <x-button
                size="auto"
                wire:click="$dispatch('open-hard-skill-modal')"
        >
            <x-heroicon name="plus"/>
            New Skill
        </x-button>
    </x-slot:action>

    <livewire:skill-modal :user="$customer"/>
    @if($hardSkills->isEmpty())
        <x-empty-state
                icon="academic-cap"
                message="No hard skills added yet"
                description="Add technical skills and expertise to showcase capabilities"
        />
    @else
        <div class="skills-grid">
            @foreach($hardSkills->slice(0, $visibleSection) as $skill)
                <x-card.skill-card :skill="$skill"/>
            @endforeach
        </div>
        @if(count($hardSkills) > $visibleSection)
            <button
                    wire:click.preserve-scroll="showMore"
                    wire:transition
                    @click.stop
                    class="text-xs text-primary-grey hover:text-primary cursor-pointer transition-colors mt-1 px-2"
            >
                Show more ({{ count($hardSkills) - $visibleSection }} remaining)
            </button>
        @elseif(count($hardSkills) !== 1 && $hardSkills->isNotEmpty())
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