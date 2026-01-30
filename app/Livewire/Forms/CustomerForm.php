<?php

namespace App\Livewire\Forms;

use App\Models\Attribute;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CustomerForm extends Form
{

    #[Validate(['required', 'string', 'max:255', 'min:5'])]
    public string $full_name = '';

    #[Validate(['required', 'string', 'email', 'max:255', 'unique:customers', 'unique:users'])]
    public string $email = '';

    #[Validate('required')]
    public ?string $phone;

    #[Validate(['required', 'date'])]
    public ?string $dob = null;

    #[Validate('required')]
    public string $gender = '';

    #[Validate(['required', 'string'])]
    public string $nationality = '';


    public array $skills = [];

    public array $attributes = [];

    public function rulesForStep(): array
    {
        return  [
            1 => [
                'full_name' => ['required', 'string', 'min:5'],
                'email' => ['required', 'email', 'max:255', 'unique:customers', 'unique:users'],
                'dob' => ['required', 'date'],
            ],

            2 => [
                'nationality' => ['required', 'string'],
                'phone' => ['required'],
                'gender' => ['required'],
            ]
        ];
    }

    public function addAttribute(Attribute $attribute, mixed $value): void
    {
        $this->attributes[$attribute->id] = compact('attribute', 'value');
    }

    public function addSkill(int $skillId, int $skillLevel, int | null $skillYears): void
    {
        $this->skills[$skillId] = [
            'selected' => true,
            'level' => $skillLevel,
            'years' => $skillYears,
        ];
    }
}
