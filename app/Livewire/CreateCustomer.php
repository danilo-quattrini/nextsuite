<?php

namespace App\Livewire;

use App\Livewire\Forms\CustomerForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCustomer extends Component
{
    use WithFileUploads;

    public CustomerForm $form;

    #[Validate('required', message: 'Customer profile photo it\'s required.')]
    #[Validate('mimes:jpeg,png,jpg,gif', message: 'Customer profile photo should be  one of this formats: jpeg,png,jpg,gif.')]
    #[Validate('image', message: 'The file must be an image.')]
    #[Validate('max:2048', message: 'Profile image it\'s too large.')]
    public $customer_photo;

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

        $imageName = strtolower(str_replace(' ', '_', $this->form->full_name)) . '.' . $this->customer_photo->extension();
        $this->customer_photo->storeAs('customers-profile-photos', $imageName, 'public');

        Customer::create([
            'name'  => $this->form->full_name,
            'profile_photo_url' => $imageName,
            'email' => $this->form->email,
            'phone' => $this->form->phone,
            'dob'   => $this->form->dob,
            'gender' => $this->form->gender,
        ]);
        return redirect()->to('/dashboard');
    }
}
