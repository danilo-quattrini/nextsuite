<?php

use App\Domain\Skill\Services\Chart\ChartFactory;
use IcehouseVentures\LaravelChartjs\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component {
    public ?Collection $data = null;

    public function buildSoftSkillChart(string $categoryName, array $labels, array $data): Builder
    {
        $chart = ChartFactory::make('bar');
        $this->softSkillChartData = $chart->buildDataset($labels, $data);

        return $chart->buildChart($categoryName, $labels, $data)
            ->livewire()
            ->model('softSkillChartData');
    }
};
?>
<div class="flex gap-8 overflow-x-auto w-full py-2">
    @foreach($data as $key => $value)
        @php
            $labels = collect($value['skills'] ?: 'Example')->pluck('name')->all();
            $data = collect($value['skills'] ?: 0)->pluck('level')->map(fn ($level) => $level ?? 0)->all();
        @endphp
        <x-card.card-container title="{{ $key }}">
            @if(isset($value['average']))
                <x-slot:action>
                    <x-average-tag size="lg" :value="$value['average']"/>
                </x-slot:action>
            @endif
            <div class="flex justify-center items-center">
                {!! $this->buildSoftSkillChart($key, $labels, $data)->render() !!}
            </div>
        </x-card.card-container>
    @endforeach
</div>