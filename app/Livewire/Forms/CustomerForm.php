<?php

namespace App\Livewire\Forms;

use App\Models\Attribute;
use App\Models\Skill;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CustomerForm extends Form
{

    #[Validate(['required', 'string', 'max:255', 'min:5'])]
    public string $full_name = '';

    #[Validate(['required', 'string', 'email', 'max:255'])]
    public string $email = '';

    #[Validate('required')]
    public ?string $phone;

    #[Validate(['required', 'date', 'before: -10 years'])]
    public ?string $dob = null;

    #[Validate('required')]
    public string $gender = '';

    #[Validate(['required', 'string'])]
    public string $nationality = '';


    public array $skills = [];

    public array $attributes = [];

//    public function defaultSkills(): void
//    {
//        $skillsByCategory = Skill::with('category')
//            ->get()
//            ->map(function (Skill $skill) {
//                    return [
//                        'id' => $skill->id,
//                        'name' => $skill->name,
//                        'type' => $skill->category->type->value
//                    ];
//            } );
//
//        foreach ($skillsByCategory as $value) {
//            $skillId = $value['id'];
//            $this->skills[$skillId] = [
//                'skill' => $value,
//                'selected' => $value['type'] === 'soft_skill',
//                'level' => 20,
//                'years' => null,
//            ];
//        }
//    }

    public function addAttribute(Attribute $attribute, mixed $value): void
    {
        $this->attributes[$attribute->id] = compact('attribute', 'value');
    }

    public function addSkill(
        User $user,
        int $skillId,
        int $skillLevel,
        int | null $skillYears
    ): void {

        $this->skills[$skillId] = [
            'skill' => $this->getSkill($skillId),
            'selected' => true,
            'level' => $skillLevel,
            'years' => $skillYears,
            'user_id' => $user
        ];
    }

    /**
     * @param  int  $skillId
     * @return Skill
     */
    private function getSkill(int $skillId): Skill
    {
        return Skill::findOrFail($skillId);
    }
}
