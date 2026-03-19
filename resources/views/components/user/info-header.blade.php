<?php

use App\Domain\Skill\Services\SoftSkillChartService;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public ?Model $user = null;

    public bool $hasReview = false;
    public bool $hasSoftSkill = false;
    public bool $hasRole = false;

    public bool $showInfo = true;
    public ?string $modelType = '';
    public ?string $modelName = '';

    public ?float $softSkillsAverage = null;
    public function mount(): void
    {
        $this->getModelDetails();
        $this->getSoftSkillsAverage();
    }

    #[Computed]
    public function getModelDetails(): void
    {
        $this->modelType = get_class($this->user);
        $this->modelName = strtolower(class_basename($this->modelType));
        $this->hasRole = method_exists(get_class($this->user), 'roles');
    }

    #[Computed]
    public function getSoftSkillsAverage(): void
    {
        $service = app(SoftSkillChartService::class);
        $skillsByCategory = $service->buildSoftSkills($this->user);

        $this->softSkillsAverage = $service->overallAverage($skillsByCategory);
    }
};
?>
<div class="user-view__header">
    <div class="user-view__identity">
        <x-profile-image
                :src="$user?->profile_photo_url"
                :name="$user?->full_name ?? 'Random User'"
                directory="{{ $this->modelName }}-profile-photos"
                size="custom"
                class="user-view__avatar"
        />

        <div class="user-view__meta">
            <div class="user-view__title">
                <h1 class="user-view__name">
                    {{ $user->full_name ?? 'User' }}
                </h1>
                @island
                    @if(!empty($softSkillsAverage))
                        <x-average-tag :value="$softSkillsAverage"/>
                    @endif
                @endisland
            </div>
            @if($hasRole)
                <x-tag
                        variant="white"
                >
                    {{ ucfirst($user?->getRoleNames()->first()) }}
                </x-tag>
            @endif
            @if($hasReview)
                @php
                    $reviewCounter = $user?->reviews_count;
                    $reviewWord = $user?->reviews_count === 1 ? 'review' : 'reviews';
                    $review = trim($reviewCounter . ' ' . $reviewWord)
                @endphp
                <div class="user-view__rating">
                    <x-heroicon variant="solid" name="star" class="text-secondary-warning"/>
                    <span>{{ number_format($user->reviews_avg_rating ?? 0, 1)  }} / 5</span>
                    <span>({{ $review }})</span>
                </div>
            @endif

            @if($showInfo)
                <x-user.user-view-subtitle icon="envelope">
                    {{ $user->email ?? 'example@gmail.com' }}
                </x-user.user-view-subtitle>
                <x-user.user-view-subtitle icon="phone">
                    {{ $user->phone ?? '+(123) 1234567890' }}
                </x-user.user-view-subtitle>
            @endif
        </div>
    </div>
</div>
