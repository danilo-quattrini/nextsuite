<?php

namespace App\Livewire\Customer;

use App\Livewire\Forms\CustomerForm;
use App\Models\Attribute;
use App\Models\Customer;
use App\Models\Skill;
use App\Services\NationalityService;
use App\Traits\ArrayOperation;
use App\Traits\WithSkill;
use App\Traits\WithStep;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCustomer extends Component
{
    use WithFileUploads;
    use WithStep;
    use ArrayOperation;
    public CustomerForm $form;

//    #[Validate('required', message: 'Customer profile photo it\'s required.')]
    #[Validate('mimes:jpeg,png,jpg,gif', message: 'Customer profile photo should be  one of this formats: jpeg,png,jpg,gif.')]
    #[Validate('image', message: 'The file must be an image.')]
    #[Validate('max:2048', message: 'Profile image it\'s too large.')]
    public $customer_photo;

    public array $nationalities = [];

    public function mount(NationalityService $nationalityService): void
    {
        $this->nationalities = $nationalityService->all();
    }

    public function render(): View
    {
        return view('livewire.customer.create-customer', [
                'nationalities' => $this->nationalities,
        ]);
    }

    #[On('attribute-selected')]
    public function attributeSelected(Attribute $attribute, mixed $value): void
    {
        $this->form->addAttribute($attribute, $value);
    }

    #[On('skill-selected')]
    public function skillSelected(int $skillId, int $skillLevel, int $skillYears): void
    {
        $this->form->addSkill($skillId, $skillLevel, $skillYears);
    }

    public function submit(): void
    {

        $this->form->validate();

        if($this->customer_photo != null) {
            $imageName = strtolower(str_replace(' ', '_',
                    $this->form->full_name)).'.'.$this->customer_photo->extension();
            $this->customer_photo->storeAs('customers-profile-photos', $imageName, 'public');
        }

        $customer = Customer::create([
            'profile_photo_url' => $imageName ?? null,
            'full_name'  => $this->form->full_name,
            'email' => $this->form->email,
            'nationality' => $this->form->nationality,
            'phone' => $this->form->phone,
            'dob'   => $this->form->dob,
            'gender' => $this->form->gender,
            'company_id' => auth()->user()->company?->id,
            'user_id' => auth()->id()
        ]);

        $skillsToAttach = [];
        $attributesToAttach = [];

        foreach ($this->form->skills as $skillId => $data) {
            if (!empty($data['selected'])) {
                $skillsToAttach[$skillId] = [
                    'level' => $data['level'],
                    'years' => $data['years'],
                ];
            }
        }

        foreach ($this->form->attributes as $attributeId => $data){
            $attributesToAttach[$attributeId] = [
              'value' => $data['value']
            ];
        }

        if(!empty($skillsToAttach) || !empty($attributesToAttach)) {
            $customer->skills()->sync($skillsToAttach);
            $customer->attributes()->sync($attributesToAttach);
        }

        $this->redirect('/customer');
    }

    protected function stepRules(): array
    {
        return $this->form->rulesForStep();
    }

}
