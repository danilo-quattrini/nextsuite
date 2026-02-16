<?php

use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public ?Model $user = null;
    public bool $hasReview = false;
    public bool $hasSoftSkill = false;
    public ?string $modelType = '';
    public ?string $modelName = '';

    public function mount(): void
    {
        $this->getModelDetails();
    }
    #[Computed]
    public function getModelDetails(): void
    {
        $this->modelType = get_class($this->user);
        $this->modelName = strtolower(class_basename($this->modelType));
    }
};
?>
<div class="user-view__header">
    <div class="user-view__identity">
        <x-profile-image
                :src="$user?->profile_photo_url"
                :name="$user?->full_name ?? 'Random User'"
                directory="{{ $this->modelName }}s-profile-photos"
                size="custom"
                class="user-view__avatar"
        />

        <div class="user-view__meta">
            <div class="user-view__title">
                <h1 class="user-view__name">
                    {{ $user->full_name ?? 'User' }}
                </h1>
                @if($hasSoftSkill)
                    <x-average-tag size="large" :value="$softSkillsAverage"/>
                @endif

            </div>
            @if($hasReview)
                <div class="user-view__rating">
                    <x-heroicon variant="solid" name="star" class="text-secondary-warning"/>
                    <span>{{ number_format($user->reviews_avg_rating ?? 0, 1) }}</span>
                    <span>({{ $user?->reviews_count }} reviews)</span>
                </div>
            @endif
            <p class="user-view__subtitle">{{ $user->email ?? 'example@gmail.com' }}
            </p>
            <p class="user-view__subtitle">{{ $user->phone ?? '+12 1234566789'  }}</p>
        </div>
    </div>
</div>
</div>