<?php

namespace App\Livewire;

use App\Livewire\Forms\CustomerForm;
use App\Models\Customer;
use App\Models\Skill;
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

    public array $skillsByCategory = [];

    protected function flagEmoji(string $code): string
    {
        // Ensure we only try to build flags for two-letter ISO codes
        $clean = strtoupper(trim($code));
        if (!preg_match('/^[A-Z]{2}$/', $clean)) {
            return '';
        }

        return mb_chr(0x1F1E6 + (ord($clean[0]) - 65))
            . mb_chr(0x1F1E6 + (ord($clean[1]) - 65));
    }

    public function getNationalitiesProperty()
    {
        return cache()->rememberForever('nationalities', function () {
            return collect(countries())
                ->map(function ($country, $code) {
                    $codeStr = (string) $code;
                    return [
                        'code' => $codeStr,
                        'name' => $country['name'] ?? $codeStr,
                        'flag' => $this->flagEmoji($codeStr),
                    ];
                })
                ->sortBy('name')
                ->values()
                ->toArray();
        });
    }

    public function mount(): void
    {
        $company = auth()->user()->company()->firstOrFail();

        $user = auth()->user();

        if ($user->company) {
            $user->company->load('field.categories.skills');

            $this->skillsByCategory = $user->company->field
                ->categories
                ->flatMap(fn ($category) => $category->skills)
                ->unique('id')
                ->groupBy(fn ($skill) => $skill->category->name)
                ->toArray();
        } else {
            // fallback: global skills
            $this->skillsByCategory = Skill::with('category')
                ->get()
                ->groupBy(fn ($skill) => $skill->category->name)
                ->toArray();
        }

    }

    public function render(): View
    {
        return view('livewire.create-customer', [
                'nationalities' => $this->nationalities,
        ]);
    }

    public function nextStep(): void
    {
        if ($this->step === 1) {
            $this->validateOnly('customer_photo');

            $this->form->validate(
                $this->form->rulesForStep(1)
            );
        }

        if ($this->step === 2) {
            $this->form->validate(
                $this->form->rulesForStep(2)
            );
        }
        $this->step++;
    }
    public function previousStep(): void
    {
        $this->step--;
    }

    public function submit(): void
    {

        $this->form->validate();

        $imageName = strtolower(str_replace(' ', '_', $this->form->full_name)) . '.' . $this->customer_photo->extension();
        $this->customer_photo->storeAs('customers-profile-photos', $imageName, 'public');

        $customer = Customer::create([
            'profile_photo_url' => $imageName,
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

        foreach ($this->form->skills as $skillId => $data) {
            if (!empty($data['selected'])) {
                $skillsToAttach[$skillId] = [
                    'level' => $data['level'],
                    'years' => $data['years'],
                ];
            }
        }

        if(!empty($skillsToAttach)) {
            $customer->skills()->sync($skillsToAttach);
        }

        $this->redirect('/customer/list');
    }
}
