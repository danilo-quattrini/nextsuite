<?php

use App\Models\Company;
use App\Models\JoinRequest;
use App\Notifications\Services\NotificationService;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination;

    public Company $company;

    public function mount(): void
    {
        // Only the owner can access this page
        if ($this->company->owner_id !== auth()->id()) {
            abort(403, 'You are not the owner of this company.');
        }
    }

    #[Computed]
    public function pendingRequests(): LengthAwarePaginator
    {

        return JoinRequest::getCompanyRequests(companyId:  $this->company->id);
    }

    public function accept(int $joinRequestId): void
    {
        $joinRequest = JoinRequest::with(['user', 'company'])
            ->findOrFail($joinRequestId);

        // Guard — only owner of this company can act
        if ($joinRequest->company->owner_id !== auth()->id()) {
            abort(403);
        }

        if (!$joinRequest->isPending()) {
            session()->flash('error', 'This request has already been handled.');
            return;
        }

        // Attach user to company as employee
        $this->company->employee()->attach($joinRequest->user_id);

        // Mark as accepted
        $joinRequest->update(['status' => 'accepted']);

        // Notify the user
        app(NotificationService::class)->sendJoinAccepted(
            user: $joinRequest->user,
            company: $this->company,
        );

        session()->flash('success', "{$joinRequest->user->full_name} has been added to your company.");
    }

    public function refuse(int $joinRequestId): void
    {
        $joinRequest = JoinRequest::with(['user', 'company'])
            ->findOrFail($joinRequestId);

        // Guard — only owner of this company can act
        if ($joinRequest->company->owner_id !== auth()->id()) {
            abort(403);
        }

        if (!$joinRequest->isPending()) {
            session()->flash('error', 'This request has already been handled.');
            return;
        }

        // Mark as refused
        $joinRequest->update(['status' => 'refused']);

        // Notify the user
        app(NotificationService::class)->sendJoinRefused(
            user: $joinRequest->user,
            company: $this->company,
        );

        session()->flash('info', "{$joinRequest->user->full_name}'s request has been refused.");
    }
};
?>

<x-card.content-page-card
        title="Join Requests"
        description="Manage users who want to join {{ $this->company->name }}"
        :has-counter="true"
        :counter-title="Str::plural('Request', $this->pendingRequests->total())"
        :counter-value="$this->pendingRequests->total()"
>

    {{-- Flash messages --}}
    <div class="space-y-2">
            @forelse ($this->pendingRequests as $request)

                <x-card.card-container card-size="lg">
                    <div class="flex items-center justify-between gap-4">

                        {{-- User info --}}
                        <div class="page-content__profile">
                            <x-profile-image
                                    :src="$request->user?->profile_photo_url"
                                    :name="$request->user->full_name"
                                    directory="profile-photos"
                                    size="custom"
                                    class="page-content__avatar-image"
                            />
                            <div>
                                <p class="page-content__name">{{ $request->user->full_name }}</p>
                                <p class="page-content__email">{{ $request->user->email }}</p>
                                <p class="text-xs text-primary-grey">
                                    Requested {{ $request->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2">

                            <x-button
                                    size="auto"
                                    variant="error"
                                    wire:click="refuse({{ $request->id }})"
                                    wire:confirm="Are you sure you want to refuse this request?"
                            >
                                <x-heroicon name="x-circle" />
                                Refuse
                            </x-button>

                            <x-button
                                    size="auto"
                                    wire:click="accept({{ $request->id }})"
                                    wire:confirm="Are you sure you want to accept this request?"
                            >
                                <x-heroicon name="check" />
                                Accept
                            </x-button>
                        </div>

                    </div>
                </x-card.card-container>

            @empty

                <x-card.card-container
                        class="col-span-3"
                        card-size="lg"
                >
                    <x-empty-state
                            icon="inbox"
                            message="No pending requests"
                            description="There are no users waiting to join your company"
                    >
                        <x-slot:action>
                            <x-button
                                    size="full"
                                    variant="white"
                                    href="{{ route('dashboard') }}"
                            >
                                <x-heroicon name="home"/>
                                Home
                            </x-button>
                        </x-slot:action>
                    </x-empty-state>
                </x-card.card-container>

            @endforelse

    </div>
    {{-- Pagination --}}
    @if ($this->pendingRequests->hasPages())
        <x-slot:pagination>
            <div class="page-content__pagination">
                {{ $this->pendingRequests->links('components.pagination', data: ['scrollTo' => false]) }}
            </div>
        </x-slot:pagination>
    @endif

</x-card.content-page-card>