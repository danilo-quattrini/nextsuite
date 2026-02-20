<?php

use App\Domain\Skill\Services\SkillService;
use App\Domain\User\Services\UserService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Attributes\Lazy;
use Livewire\Component;

new #[Lazy] class extends Component {

    public ?Model $user = null;
    public ?string $summary = '';
    public ?Collection $hardSkills = null;

    public function mount(): void
    {
        $service = new UserService($this->user);
        $this->summary = $service->getUserReview();
    }


    // This is the placeholder shown WHILE mount() is running
    public function placeholder(): string
    {
        return <<<'HTML'
            <div class="user-view__panel user-view__panel--wide">
                <div class="user-view__panel-header">
                    <div class="user-view__panel-header--left">
                        <h3>Summary</h3>
                        <p class="user-view__panel-subtitle">That's a quick review about the customer.</p>
                        <div class="summary-skeleton">
                            <div class="skeleton-line"></div>
                            <div class="skeleton-line skeleton-line--short"></div>
                            <div class="skeleton-line"></div>
                        </div>
                    </div>
                </div>
            </div>
        HTML;
    }
};
?>

<div class="user-view__panel user-view__panel--wide">
    <div class="user-view__panel-header">
        <div class="user-view__panel-header--left">
            <h3>Summary</h3>
            <p class="user-view__panel-subtitle">That's a quick review about the customer.</p>
            <p>{{ $summary }}</p>
        </div>
    </div>
</div>