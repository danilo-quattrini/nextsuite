<?php

use App\Models\Company;
use App\Models\JoinRequest;
use App\Models\User;
use App\Notifications\Services\NotificationService;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination;

    #[Computed(cache: false)]
    public function companies(): LengthAwarePaginator
    {
        return Company::getCompanies(page: $this->getPage());
    }

    /**
     * Method that sends a notification
     * that a user wants to join into a company
     *
     * @param  int  $companyId  , company id where the user wants to join
     * @param  int  $userId  , user where send the request
     * */
    #[On('request-join')]
    public function requestJoin(
        int $companyId,
        int $userId
    ): void {
        $company = Company::findOrFail($companyId);
        $user = User::findOrFail($userId);
        $owner = $company->users;

        // Block if already a member
        if ($company->employee()->where('employee_id', $userId)->exists()) {
            session()->flash('error', 'You are already a member of this company.');
            return;
        }

        // Block if request already pending
        $alreadyRequested = JoinRequest::where('user_id', $userId)
            ->where('company_id', $companyId)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyRequested) {
            session()->flash('info', 'You already have a pending request for this company.');
            return;
        }

        // Create the join request record
        JoinRequest::create([
            'user_id' => $userId,
            'company_id' => $companyId,
            'status' => 'pending',
        ]);

        // route('company.requests', $companyId)
        if ($owner) {
            app(NotificationService::class)->send(
                notifiable: $owner,
                subject: "Request to join into your company",
                message: "There's a request from the user **{$user->full_name}** to join into your company.",
                actionText: "Review Request",
                actionUrl:  route('company.requests', $owner->company),
                channels: ['mail', 'database']
            );
            session()->flash('info', 'Your request has been sent to '.$owner->full_name);
        }
    }

    /**
     * Owner accepts a join request.
     * Attaches user to company, notifies user.
     */
    #[On('accept-join')]
    public function acceptJoin(int $joinRequestId): void
    {
        $joinRequest = JoinRequest::with(['user', 'company'])->findOrFail($joinRequestId);

        if (! $joinRequest->isPending()) {
            session()->flash('error', 'This request has already been handled.');
            return;
        }

        // Attach user to company
        $joinRequest->company->employee()->attach($joinRequest->user_id);

        // Mark request as accepted
        $joinRequest->update(['status' => 'accepted']);

        // Notify the user
        app(NotificationService::class)->sendJoinAccepted(
            user:    $joinRequest->user,
            company: $joinRequest->company,
        );

        session()->flash('success', "{$joinRequest->user->full_name} has been added to the company.");
    }

    /**
     * Owner refuses a join request.
     * Notifies user of the decision.
     */
    #[On('refuse-join')]
    public function refuseJoin(int $joinRequestId): void
    {
        $joinRequest = JoinRequest::with(['user', 'company'])->findOrFail($joinRequestId);

        if (! $joinRequest->isPending()) {
            session()->flash('error', 'This request has already been handled.');
            return;
        }

        // Mark request as refused
        $joinRequest->update(['status' => 'refused']);

        // Notify the user
        app(NotificationService::class)->sendJoinRefused(
            user:    $joinRequest->user,
            company: $joinRequest->company,
        );

        session()->flash('info', "{$joinRequest->user->full_name}'s request has been refused.");
    }
};
?>

<x-card.content-page-card
        title="Companies"
        description="Select a company where you want to join"
        :has-counter="true"
        :has-grid="true"
        :counter-title="Str::plural('Company', count($this->companies) ?? 0)"
        :counter-value="$this->companies->total()"
>

    @forelse ($this->companies as $company)
        <x-company-card :company="$company" :showFields="true">

            {{-- ── Join button injected via slot ──────────── --}}
            <x-button
                    size="full"
                    wire:click="$dispatch('request-join', { companyId: {{ $company->id }} , userId: {{ Auth::user()->id }}})"
            >
                Request to join
            </x-button>

        </x-company-card>
    @empty
        <x-card.card-container
                class="col-span-3"
                card-size="lg"
        >
            <x-empty-state
                    icon="building-office"
                    message="No company has been created or founded"
                    description="Try again or create a company by your own"
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
                    <p> or </p>
                    <x-button
                            size="full"
                            href="{{route('company.create')}}"
                    >
                        <x-heroicon name="plus"/>
                        Create Company
                    </x-button>
                </x-slot:action>
            </x-empty-state>
        </x-card.card-container>
    @endforelse

    {{-- ── Pagination ───────────────────────────────────────── --}}
    @if ($this->companies->hasPages())
        <x-slot:pagination>
            <div class="page-content__pagination">
                {{ $this->companies->links('components.pagination', data: ['scrollTo' => false]) }}
            </div>
        </x-slot:pagination>
    @endif
</x-card.content-page-card>