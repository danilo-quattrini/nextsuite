<?php

namespace App\Livewire;

use App\Livewire\Forms\CustomerForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Livewire\Component;

class CreateCustomer extends Component
{
    public CustomerForm $form;

    public $step = 1;

    public function render(): View
    {
        return view('livewire.create-customer');
    }

    public function nextStep(): void
    {
        $this->step++;
    }
    public function previousStep(): void
    {
        $this->step--;
    }

    public function save(): RedirectResponse
    {
        $this->form->validate();

        Customer::create([
            'name'  => $this->form->full_name,
            'email' => $this->form->email,
            'phone' => $this->form->phone,
            'dob'   => $this->form->dob,
            'gender' => $this->form->gender,
        ]);
        return redirect()->to('/dashboard');
    }
}
