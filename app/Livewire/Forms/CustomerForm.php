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

    #[Validate(['required', 'string', 'size:2'])]
    public string $nationality = '';

    #[Validate([
        'skills.*.selected' => ['boolean'],
        'skills.*.level' => ['nullable', 'integer', 'min:1', 'max:5', 'required_with:skills.*.selected'],
        'skills.*.years' => ['nullable', 'integer', 'min:0', 'required_with:skills.*.selected']
    ])]
    public array $skills = [];

    public function rulesForStep(int $step): array
    {
        return match ($step) {
            1 => [
                'full_name' => ['required', 'string', 'min:5'],
                'email' => ['required', 'email'],
                'nationality' => ['required', 'string', 'size:2'],
            ],

            2 => [
                'phone' => ['required'],
                'dob' => ['required', 'date'],
                'gender' => ['required'],

                'skills' => ['array'],
                'skills.*.selected' => ['boolean'],
                'skills.*.level' => [
                    'nullable', 'integer', 'min:1', 'max:5',
                    'required_with:skills.*.selected',
                ],
                'skills.*.years' => [
                    'nullable', 'integer', 'min:0',
                    'required_with:skills.*.selected',
                ],
            ],

            default => [],
        };
    }
}
