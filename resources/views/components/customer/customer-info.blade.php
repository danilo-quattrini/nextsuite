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
            'Email' => $this->customer->email ?? 'N.A',
            'Phone' => $this->customer->phone ?? 'N.A',
            'Date of birth' => $this->customer->dob->format('d/m/Y') ?? 'N.A',
            'Gender' => $this->customer->gender ?? 'N.A',
            'Nationality' => $this->customer->nationality ?? 'N.A'
        ];
    }
};
?>
<x-card.card-container title="Personal Info">
    @foreach($userInfo as $label => $value)
        <p><strong>{{ $label }}</strong> {{ $value }} </p>
    @endforeach
</x-card.card-container>