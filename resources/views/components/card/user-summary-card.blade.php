<?php

use App\Domain\Skill\Contracts\SkillAssignable;
use App\Domain\Skill\Services\SkillService;
use App\Domain\User\Services\UserService;
use App\Jobs\GenerateUserReview;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Attributes\Lazy;
use Livewire\Component;

new #[Lazy]
class extends Component {

    public ?SkillAssignable $user = null;
    public ?string $summary = '';
    public ?array $skills = null;

    public function mount(): void
    {
        if ($this->user->hasSkill()) {
            $userService = new UserService($this->user);
            $this->summary = $userService->getReview();

            if ($this->summary === '') {
                $userService->generateUserReview();
                $this->summary = $userService->getReview();
            }
        }
    }


    // This is the placeholder shown WHILE mount() is running
    public function placeholder(): string
    {
        return <<<'HTML'
                <x-card.card-container
            title="Summary"
            subtitle="That's a quick review about the customer"
                    >
                        <div class="summary-skeleton">
                            <div class="skeleton-line"></div>
                            <div class="skeleton-line skeleton-line--short"></div>
                            <div class="skeleton-line"></div>
                        </div>
               </x-card.card-container>
        HTML;
    }
};
?>

<x-card.card-container
        title="Summary"
        subtitle="That's a quick review about the customer"
        class="text-wrap"
>
    @if($this->user->hasSkill())
        <p class="indent-0">{{ $summary }}</p>
    @else
        <x-empty-state
                icon="cube-transparent"
                message="This customer has no skill added yet"
                description="Add a skill or schema to this customer to get an evaluation"
        />
    @endif
</x-card.card-container>
