<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class CustomerForm extends Form
{
    use WithFileUploads;
    #[Validate('required|image|mimes:jpeg,png,jpg,gif', message: 'Customer profile phot it\'s required')]
    #[Validate('max:2048', message: 'Profile image it\'s too large' )]
    public $customer_photo;

    #[Validate(['required', 'string', 'max:255', 'min:5'])]
    public string $full_name = '';

    #[Validate(['required', 'string', 'email', 'max:255', 'unique:users'])]
    public string $email = '';

    #[Validate('required')]
    public string $phone = '';

    #[Validate(['required', 'date'])]
    public string $dob = '';

    #[Validate('required')]
    public string $gender = '';

}
