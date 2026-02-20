<div class="user-view">
    {{-- HEADER --}}
    <livewire:user.info-header :user="$customer" has-review="true"/>

    {{-- QUICK STATS --}}
    <section class="user-view__stats">
        <div class="user-view__stat-card">
            <p class="user-view__stat-label">Profile completion</p>
            <p class="user-view__stat-value">82%</p>
            <p class="user-view__stat-hint">+6% this month</p>
        </div>
        <div class="user-view__stat-card">
            <p class="user-view__stat-label">Engagement score</p>
            <p class="user-view__stat-value">74</p>
            <p class="user-view__stat-hint">Stable trend</p>
        </div>
        <div class="user-view__stat-card">
            <p class="user-view__stat-label">Documents created</p>
            <p class="user-view__stat-value">18</p>
            <p class="user-view__stat-hint">Last 90 days</p>
        </div>
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

    <section class="user-view__grid">

        {{-- FIELD CHART --}}
        <div class="user-view__panel user-view__panel--wide">
            {{-- CARD HEADER --}}
            <div class="user-view__panel-header">

                {{-- HEADING & SUBTITLE --}}
                <div>
                    <h3>Field Skills</h3>
                    <p class="user-view__panel-subtitle">Average level by field</p>
                </div>

                {{-- DROPDOWN BUTTON --}}
                <div class="flex justify-between items-center gap-8">
                    @foreach ($chartTypes as $type)
                        <x-radio
                                id="chartype-{{$type}}"
                                name="chartype-{{$type}}"
                                value="{{ $type }}"
                                wire:model.live.debounce.150ms="fieldChartType"
                        >
                            {{ ucfirst($type) }}
                        </x-radio>
                    @endforeach
                </div>
            </div>

            {{-- GRAPH --}}
            @if($fieldSkills && $fieldSkills->isNotEmpty())
                @php
                    $fieldLabels = $fieldSkills->keys()->all();
                    $fieldData = $fieldSkills->map(fn ($group) => $group['average'] ?? 0)->values()->all();
                @endphp

                <div class="user-view__skill-chart">
                    {!! $this->buildFieldSkillChart('Field Skills', $fieldLabels, $fieldData)->render() !!}
                </div>
                <div class="user-view__field-lists">
                    @foreach($fieldSkills as $fieldName => $group)
                        <div class="user-view__field-list">
                            <div class="user-view__field-summary">
                                <h3>{{ $fieldName }}</h3>
                                <x-average-tag size="large" :value="$group['average']" />
                            </div>
                            @foreach(collect($group['skills'] ?? []) as $skill)
                                <div class="user-view__field-item">
                                    <span>{{ $skill['name'] }}</span>
                                    <span class="user-view__field-score">
                                        {{ is_numeric($skill['level'] ?? null) ? number_format($skill['level'], 1) : '0.0' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @else
                <p class="user-view__empty">No field skills available yet.</p>
            @endif
        </div>

        {{-- SKILL CHART --}}
        <div class="user-view__panel user-view__panel--wide">
            {{-- HEADING & SUBTITLE --}}
            <div class="user-view__panel-header">
                <div>
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
