@props(['skill'])

<div class="skill-card">
    {{-- Header --}}
    <div class="skill-card__header">
        <div class="skill-card__title-wrapper">
            <div class="skill-card__title-group">
                <h4 class="skill-card__title">{{ $skill->name }}</h4>
                <span class="skill-card__subtitle"> {{ $skill->description ?? '' }}</span>
            </div>
        </div>
    </div>

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
</div>
