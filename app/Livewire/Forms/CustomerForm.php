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

    #[Validate(['required', 'string'])]
    public string $nationality = '';


    public array $skills = [];

    public function rulesForStep(): array
    {
        return  [
            1 => [
                'full_name' => ['required', 'string', 'min:5'],
                'email' => ['required', 'email'],
                'nationality' => ['required', 'string'],
            ],

            2 => [
                'phone' => ['required'],
                'dob' => ['required', 'date'],
                'gender' => ['required'],
            ]
        ];
    }
}
