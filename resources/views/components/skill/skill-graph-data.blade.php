<?php

use App\Domain\Skill\Contracts\SkillAssignable;
use App\Domain\Skill\Services\Chart\ChartFactory;
use App\Domain\Skill\Services\SkillChart\HardSkillChartService;
use App\Domain\Skill\Services\SkillChart\SoftSkillChartService;
use IcehouseVentures\LaravelChartjs\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\Lazy;
use Livewire\Component;

new #[Lazy]
class extends Component {
    public ?SkillAssignable $user;
    public array $chartTypes = [];
    public string $chartType = 'pie';
    public ?Collection $skills = null;

    public function mount(): void
    {
        $this->chartTypes = ['bar', 'line', 'pie', 'doughnut', 'radar'];
        $this->getFieldSkills($this->user);
    }

    private function getFieldSkills(?SkillAssignable $user): void
    {
        $service = new HardSkillChartService($user, 'hard');

        $this->skills = $service->getChartData();
    }

    public function buildSkillChart(string $chartName, array $labels, array $data): Builder
    {
        $chart = ChartFactory::make($this->chartType);
        $this->chartDataField = $chart->buildDataset($labels, $data);

        return $chart->buildChart($chartName, $labels, $data)
            ->livewire()
            ->model("chartDataField");
    }
};
?>

<x-card.card-container
        title="Hard Skills"
        subtitle="Average level by field"
>
    @if($skills->isNotEmpty())
        {{-- DROPDOWN BUTTON --}}
        <x-slot:action>
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
        </x-slot:action>
            @php
                $skillLabels = $skills->keys()->all();
                $skillData = $skills->map(fn ($group) => $group['average'] ?? 0)->values()->all();
            @endphp

            {{-- GRAPH --}}
            <div class="user-view__skill-chart">
                {!! $this->buildSkillChart('Skills', $skillLabels, $skillData)->render() !!}
            </div>
            <div class="user-view__field-lists">
                @foreach($skills as $skillName => $group)
                    <div class="user-view__field-list">
                        <div class="user-view__field-summary">
                            <h3>{{ $skillName }}</h3>
                            <x-average-tag size="large" :value="$group['average']"/>
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
        <x-empty-state
                icon="star"
                message="No skills added yet"
                description="Add an skills or a skill schema to this customer to have a report"
        />
        <div class="flex justify-center items-center">
            <x-button
                    size="auto"
                    wire:click="$dispatch('open-hard-skill-modal')"
            >
                <x-heroicon name="plus"/>
                New Skill
            </x-button>
        </div>
        <livewire:skill-modal :user="$user"/>
    @endif
</x-card.card-container>
