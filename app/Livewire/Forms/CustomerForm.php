<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CustomerForm extends Form
{

    #[Validate(['required', 'string', 'max:255', 'min:5'])]
    public string $full_name = '';

    #[Validate(['required', 'string', 'email', 'max:255', 'unique:customers', 'unique:users'])]
    public string $email = '';

    #[Validate('required')]
    public string $phone = '';

    #[Validate(['required', 'date'])]
    public string $dob = '';

    #[Validate('required')]
    public string $gender = '';

}
