<?php

namespace App\Livewire\Customer;

use App\Domain\Role\Services\RoleService;
use App\Livewire\Forms\CustomerForm;
use App\Models\Customer;
use App\Services\NationalityService;
use App\Traits\WithStep;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditCustomer extends Component
{
    use WithFileUploads;
    use WithStep {
        nextStep as traitNextStep;
    }

    public $oldCustomerPhoto;
    public $newCustomerPhoto;

    public array $nationalities = [];
    public array $roles = [];

    public Customer $customer;
    public CustomerForm $form;

    public function mount(
        NationalityService $nationalityService,
        RoleService $roleService
    ): void
    {

        $this->oldCustomerPhoto = $this->customer->profile_photo_url ?? null;
        $this->form->full_name = $this->customer->full_name;
        $this->form->email = $this->customer->email;
        $this->form->dob = date_format($this->customer->dob, 'Y-m-d');
        $this->nationalities = $nationalityService->all();
        $this->form->nationality = $this->customer->nationality;
        $this->form->phone = $this->customer->phone;
        $this->form->gender = $this->customer->gender;
        $this->form->role = $this->customer->roles->first()->name;
        $this->roles = $roleService->getAllRoleNames();
    }

    public function edit(
        NationalityService $nationalityService
    ): void
    {
        $this->validate();

        $imageName = $this->getImageName();

        $this->customer->update([
            'profile_photo_url' => $imageName ?? $this->oldCustomerPhoto,
            'full_name'  => $this->form->full_name,
            'email' => $this->form->email,
            'nationality' => $this->form->nationality,
            'nationality_iso' => $nationalityService->codeFromName($this->form->nationality),
            'phone' => $this->form->phone,
            'dob'   => $this->form->dob,
            'gender' => $this->form->gender,
        ]);

        $this->customer->syncRoles([$this->form->role]);

        $this->redirect(route('customer.show',  ['customer' => $this->customer->id]), navigate: true);
    }

    public function getImageName(): ?string
    {
        if ($this->newCustomerPhoto != null) {
            $imageName = strtolower(str_replace(' ', '_',
                    $this->form->full_name)).'.'.$this->newCustomerPhoto->extension();
            $this->newCustomerPhoto->storeAs('customer-profile-photos', $imageName, 'public');
        }
        return $imageName ?? '';
    }

    // ====== VALIDATION OPERATION =====
    protected function stepRules(): array
    {
        return  [
            1 => [
                'full_name' => [
                    'required',
                    'string',
                    'min:5'
                ],
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    'unique:customers,email,' . $this->customer->id,
                    'unique:users,email,' . ($this->customer->id ?? 'NULL'),
                ],
                'dob' => [
                    'required',
                    'date',
                    'before:-18 years'
                ],
            ],
            2 => [
                'nationality' => [
                    'required',
                    'string'
                ],
                'phone' => ['required'],
                'gender' => ['required'],
            ]
        ];
    }

    public function nextStep(): void
    {
        if ($this->step === 1 && $this->newCustomerPhoto !== null) {
            $this->validate(
                [
                    'newCustomerPhoto' => [
                        'nullable',
                        'mimes:jpeg,png,jpg,webp',
                        'image',
                        'max:2048'
                    ],
                ],
                [
                    'newCustomerPhoto.mimes' => 'Photo must be jpeg, png, jpg or webp.',
                    'newCustomerPhoto.max'   => 'Photo must not exceed 2MB.'
                ]
            );
        }

        $this->traitNextStep();
    }

    protected function stepValidationMessages(): array
    {
        return [
            1 => [
                'full_name.required' => 'Please enter the customer\'s full name.',
                'full_name.min'      => 'The name must be at least 5 characters.',
                'email.required'     => 'An email address is required.',
                'email.unique'       => 'This email is already registered.',
                'dob.required'       => 'Date of birth is required.',
                'dob.before'         => 'Customer must be at least 18 years old.',
            ],
            2 => [
                'nationality.required' => 'Please select a nationality.',
                'phone.required'       => 'A phone number is required.',
                'gender.required'      => 'Please select a gender.',
            ]
        ];
    }

    public function render(): View
    {
        return view('livewire.customer.edit-customer');
    }
}
