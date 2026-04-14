@props(['skill', 'user', 'editable' => false])

<x-card.card-container
        title="{{ $skill->name }}"
        subtitle="{{ $skill->description ?? '' }}"
        size="lg"
        card-size="sm"
>
    {{-- Actions --}}
    @if($editable && $user)
        <x-slot:action>
            <x-button
                    variant="error"
                    size="icon"
                    wire:click.prevent="$wire.removeSkill({{ $skill->id }})"
            >
                <x-heroicon
                        name="trash"
                        size="lg"
                />
            </x-button>
        </x-slot:action>
    @endif

    {{-- Stats Grid --}}
    <div class="skill-card__stats">
        {{-- Experience --}}
        <div class="skill-stat">
            <div class="skill-stat__label">
                <x-heroicon size="md" name="calendar" variant="outline" class="skill-stat__icon" />
                <span>Experience</span>
            </div>
            <div class="skill-stat__value">
                {{ $skill->pivot->years ?? 0 }}
                <span class="skill-stat__unit">
                    {{ Str::plural('year', $skill->pivot->years ?? 0) }}
                </span>
            </div>
        </div>

        {{-- Knowledge Level --}}
        <div class="skill-stat">
            <div class="skill-stat__label">
                <x-heroicon name="chart-bar" variant="outline" class="skill-stat__icon" />
                <span>Proficiency</span>
            </div>
            <div class="skill-stat__value-wrapper">
                <x-proficiency-badge
                        :level="$skill->pivot->level ?? 0"
                        :showLabel="true"
                />
            </div>
        </div>
    </div>
</x-card.card-container>
