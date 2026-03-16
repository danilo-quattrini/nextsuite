<div class="user-view">
    {{-- HEADER --}}

    {{--  USER INFO SECTION  --}}
    <div class="user-view__header">
        <livewire:user.info-header
                :user="$customer"
                has-review="true"
        />
    </div>

    {{-- QUICK STATS --}}
    <section class="user-view__stats">

        {{-- USER SUMMARY OVERVIEW --}}
        <livewire:card.user-summary-card :user="$customer" />

        {{-- USER FIELD SUGGESTION --}}
       <livewire:card.user-suggest-card :user="$customer"/>

        <div class="user-view__stat-card">
            <p class="user-view__stat-label">Response time</p>
            <p class="user-view__stat-value">2.4h</p>
            <p class="user-view__stat-hint">Top 20%</p>
        </div>
    </section>


    {{-- ANALYTICS --}}
    @php
        $chartTypes  = ['bar', 'line', 'pie', 'doughnut', 'radar'];
    @endphp

    <livewire:skill.skill-graph-data :user="$customer"/>

    <section class="user-view__grid">

        {{-- SKILL CHART --}}
        <div class="user-view__panel user-view__panel--wide">
            {{-- HEADING & SUBTITLE --}}
            <div class="user-view__panel-header">
                <div class="user-view__panel-header--left">
                    <h3>Soft Skill Diagram</h3>
                    <p class="user-view__panel-subtitle">Average level by soft skill</p>
                </div>

                {{-- RADIO BUTTONS --}}
                <div class="flex justify-between items-center gap-8">
                    @foreach ($chartTypes as $type)
                        <x-radio
                                id="chartype-{{$type}}"
                                name="chartype-{{$type}}"
                                value="{{ $type }}"
                                wire:model.live.debounce.150ms="chartType"
                        >
                            {{ ucfirst($type) }}
                        </x-radio>
                    @endforeach
                </div>
            </div>

            {{-- GRAPH --}}
            <div class="user-view__skills-grid">
                @if($softSkills && $softSkills->isNotEmpty())

                        @foreach($softSkills as $categoryName => $group)
                        <div class="user-view__skill-card">
                            @php
                                $labels = collect($group['skills'])->pluck('name')->all();
                                $data = collect($group['skills'])->pluck('level')->map(fn ($level) => $level ?? 0)->all();
                            @endphp
                            <div class="user-view__skill-card-header">
                                <div>
                                    <p class="user-view__skill-card-title">{{ $categoryName }}</p>
                                    <p class="user-view__skill-card-subtitle">Soft skill category</p>
                                </div>
                            </div>
                            <div class="user-view__skill-chart">
                                {!! $this->buildSoftSkillChart($categoryName, $labels, $data)->render() !!}
                            </div>
                        </div>
                        @endforeach
                @else
                    <p class="user-view__empty">No soft skills available yet.</p>
                @endif
            </div>
        </div>

    </section>

    {{--  RECENT ACTIVITY CARD  --}}
    <livewire:card.activity-card :user="$customer" />
</div>
