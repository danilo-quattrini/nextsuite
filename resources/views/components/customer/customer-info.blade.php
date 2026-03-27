<?php

use App\Models\Customer;
use Livewire\Component;

new class extends Component {
    public ?Customer $customer = null;
    public ?array $userInfo = [];

    public function mount(): void
    {
        $this->getUserInfo();
    }

    public function getUserInfo(): void
    {
        $this->userInfo = [
            [
                'icon' => "envelope",
                'label' => 'Email',
                'data' => $this->customer->email ?? "N.A"
            ],
            [
                'icon' => "phone",
                'label' => 'Phone',
                'data' => $this->customer->phone ?? "N.A"
            ],
            [
                'icon' => "calendar-days",
                'label' => 'Date of birthday',
                'data' => $this->customer->dob?->format('d/m/Y') ?? "N.A"
            ],
            [
                'icon' => "user",
                'label' => 'Gender',
                'data' => ucfirst($this->customer->gender) ?? "N.A"
            ],
            [
                'icon'     => null,
                'label' => 'Nationality',
                'iso_code' => $this->customer->nationality_iso ?? null,
                'data'     => $this->customer->nationality ?? "N.A"
            ]
        ];
    }
};
?>
<x-card.card-container title="Personal Info">
    @foreach($userInfo as $index => $value)
        <div class="flex-1 justify-start items-center gap-sm">
            <div class="flex items-center gap-3 py-2.5
                        {{ !$loop->last ? 'border-b border-outline-grey' : '' }}">

                {{-- Icon or flag --}}
                <div class="flex items-center justify-center shrink-0">
                    @if(!empty($value['iso_code']))
                        <x-nationality-flag :code="$value['iso_code']" />
                    @else
                        <x-heroicon :name="$value['icon']" size="lg"
                                    class="text-primary-grey" />
                    @endif
                </div>

                {{-- Label --}}
                <span class="flex-1 text-primary-grey w-20 shrink-0">
                    {{ $value['label'] }}
                </span>

                {{-- Value --}}
                <span class="text-primary ml-auto">
                    {{ $value['data'] }}
                </span>
            </div>
        </div>
    @endforeach
</x-card.card-container>