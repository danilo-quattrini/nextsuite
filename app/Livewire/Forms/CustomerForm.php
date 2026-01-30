<?php

namespace App\Livewire\Forms;

use App\Models\Attribute;
use App\Models\Skill;
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
    public function defaultSkills(): void
    {
        $skillsByCategory = Skill::with('category')
            ->get()
            ->map(function (Skill $skill) {
                    return [
                        'id' => $skill->id,
                        'name' => $skill->name,
                        'type' => $skill->category->type->value
                    ];
            } );

        foreach ($skillsByCategory as $value) {
            $skillId = $value['id'];
            $this->skills[$skillId] = [
                'skill' => $value,
                'selected' => $value['type'] === 'soft_skill',
                'level' => 0,
                'years' => null,
            ];
        }
    }

    public function addAttribute(Attribute $attribute, mixed $value): void
    {
        $this->attributes[$attribute->id] = compact('attribute', 'value');
    }

    public function addSkill(int $skillId, int $skillLevel, int | null $skillYears): void
    {
        $this->skills[$skillId] = [
            'skill' => $this->skills[$skillId]['skill'],
            'selected' => true,
            'level' => $skillLevel,
            'years' => $skillYears,
        ];
    }
}
