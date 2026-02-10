<div class="user-view">
    {{-- HEADER --}}
    <div class="user-view__header">
        <div class="user-view__identity">
            <x-profile-image
                :src="$customer->profile_photo_url"
                :name="$customer->full_name"
                directory="customers-profile-photos"
                size="custom"
                class="user-view__avatar"
            />

            <div class="user-view__meta">
                <div class="user-view__title">
                    <h1 class="user-view__name">
                        {{ $customer->full_name }}
                    </h1>
                    <x-average-tag size="large" :value="$softSkillsAverage" />
                </div>
                <div class="user-view__rating">
                    <x-heroicon variant="solid" name="star" class="text-secondary-warning" />
                    <span>{{ number_format($customer->reviews_avg_rating ?? 0, 1) }}</span>
                    <span>({{ $customer->reviews_count }} reviews)</span>
                </div>
                <p class="user-view__subtitle">Customer performance report</p>
            </div>
        </div>

        <div class="user-view__actions">

        </div>
    </div>

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
                <x-form.dropdown-button align="right">
                    <x-slot:trigger>
                        <x-button
                                type="button"
                                variant="white"
                                size="auto"
                                aria-label="choose which type of diagram"
                        >
                            <x-heroicon name="ellipsis-vertical" />
                        </x-button>
                    </x-slot:trigger>

                    <x-slot:content>
                        <div class="flex-col items-center space-y-3">
                            <div class="flex flex-col space-y-2 min-w-40">
                                <h6>Change Diagram</h6>
                                @foreach ($chartTypes as $type)
                                    <button
                                            type="button"
                                            wire:click.prevent="$set('fieldChartType', '{{ $type }}')"
                                            class="flex items-center gap-2 px-3 py-2 rounded-md text-sm  hover:bg-outline-grey cursor-pointer transition duration-150"
                                    >
                                        <span>{{ ucfirst($type) }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </x-slot:content>
                </x-form.dropdown-button>

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

                {{-- DROPDOWN BUTTON --}}
                <x-form.dropdown-button align="right">
                    <x-slot:trigger>
                        <x-button
                                type="button"
                                variant="white"
                                size="auto"
                                aria-label="choose which type of diagram"
                        >
                            <x-heroicon name="ellipsis-vertical" />
                        </x-button>
                    </x-slot:trigger>

                    <x-slot:content>
                        <div class="flex-col items-center space-y-3">
                            <div class="flex flex-col space-y-2 min-w-40">
                                <h6>Change Diagram</h6>
                                @foreach ($chartTypes as $type)
                                    <button
                                            type="button"
                                            wire:click.prevent="$set('chartType', '{{ $type }}')"
                                            class="flex items-center gap-2 px-3 py-2 rounded-md text-sm  hover:bg-outline-grey cursor-pointer transition duration-150"
                                    >
                                        <span>{{ ucfirst($type) }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </x-slot:content>
                </x-form.dropdown-button>

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

    <section class="user-view__grid" >
        <div class="user-view__panel user-view__panel--wide">
            <div class="user-view__panel-header">
                <h3>Recent activity</h3>
                <span class="user-view__panel-tag">Timeline</span>
            </div>

            <div class="user-view__timeline">
                <div class="user-view__timeline-item">
                    <span class="user-view__dot"></span>
                    <div>
                        <p class="user-view__timeline-title">Completed onboarding session</p>
                        <p class="user-view__timeline-meta">2 days ago</p>
                    </div>
                </div>
                <div class="user-view__timeline-item">
                    <span class="user-view__dot"></span>
                    <div>
                        <p class="user-view__timeline-title">Generated new contract</p>
                        <p class="user-view__timeline-meta">1 week ago</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
