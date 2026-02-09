<?php

namespace App\Livewire\Sidebar;

use Illuminate\Support\Collection;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class Changelog extends Component
{
    public ?Collection $changeLogs = null;

    public function mount(): void
    {
        $this->changeLogs = Activity::all()
            ->sortByDesc('created_at');
    }

    public function logTypeKey(Activity $changeLog): string
    {
        return match ($changeLog->description ?? 'event') {
            'created' => 'create',
            'updated' => 'update',
            'deleted' => 'delete',
            'edited' => 'edit',
            default => 'other',
        };
    }

    public function logState(Activity $changeLog): string
    {
        return match ($changeLog->description ?? 'event') {
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'edited' => 'Edited',
            default => 'Activity',
        };
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
    public function render()
    {
        return view('livewire.sidebar.changelog');
    }
}
