<?php

use App\Domain\Skill\Contracts\SkillAssignable;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

new class extends Component {
    public ?Collection $activities = null;
    public ?Model $user = null;

    public function mount(): void
    {
        $this->loadActivities();
    }

    public function loadActivities(): void
    {
        $this->activities = Activity::all()
            ->where('subject_id', $this->user->id)
            ->where('event', '<>', 'created')
            ->sortBy('created_at');
    }
    public function logSubjectLabel(Activity $changeLog): string
    {
        return $changeLog->subject_type ? class_basename(strtolower($changeLog->subject_type)) . ' ' .  $changeLog->subject?->full_name : 'record';
    }

    public function logCauserLabel(Activity $changeLog): string
    {
        return $changeLog->causer?->full_name ?? 'System';
    }

    public function logTimestamp(Activity $changeLog): string
    {
        return $changeLog->created_at?->format('M j, Y g:ia') ?? '';
    }

    public function logPropertiesLabel(Activity $changeLog): string
    {
        return $changeLog->properties['name'] ?? ' ';
    }
};
?>

<x-card.card-container
        title="Recent Activities"
        subtitle="All actions made on this customer"
>
        <div class="user-view__timeline">
            @if($activities->isNotEmpty())
                @foreach($activities as $activity)
                    @php
                        $logMessage = trim($this->logCauserLabel($activity) .' '. $activity->description);
                    @endphp
                    <div class="user-view__timeline-item">
                        <span class="user-view__dot"></span>
                        <div class="space-y-1">
                            <p class="user-view__timeline-title">{{ $logMessage }} <strong>{{ strtolower($this->logPropertiesLabel($activity)) }}</strong></p>
                            <p class="user-view__timeline-meta">{{ $this->logTimestamp($activity) }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <x-empty-state
                        icon="clock"
                        message="Nothing happen yet!"
                        description="This customer didn't receive anything yet"
                />
            @endif
        </div>
</x-card.card-container>