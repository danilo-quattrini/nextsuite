<?php

namespace App\Livewire\Customer;

use App\Domain\Role\Services\RoleService;
use App\Livewire\Forms\CustomerForm;
use App\Models\Customer;
use App\Services\NationalityService;
use App\Traits\WithStep;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Activitylog\Models\Activity;
use Storage;

class EditCustomer extends Component
{
    use WithFileUploads;
    use WithStep;

    public $oldCustomerPhoto;
    #[Validate('mimes:jpeg,png,jpg,gif', message: 'Customer profile photo should be  one of this formats: jpeg,png,jpg,gif.')]
    #[Validate('image', message: 'The file must be an image.')]
    #[Validate('max:2048', message: 'Profile image it\'s too large.')]
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

    public function edit(): void
    {
        $this->validate();

        $imageName = $this->getImageName();

        $this->customer->update([
            'profile_photo_url' => $imageName ?? $this->oldCustomerPhoto,
            'full_name'  => $this->form->full_name,
            'email' => $this->form->email,
            'nationality' => $this->form->nationality,
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

    protected function stepRules(): array
    {
        return  [
            1 => [
                'full_name' => ['required', 'string', 'min:5'],
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    'unique:customers,email,' . $this->customer->id,
                    'unique:users,email,' . ($this->customer->id ?? 'NULL'),
                ],
                'dob' => ['required', 'date', 'before:-10 years'],
            ],
            2 => [
                'nationality' => ['required', 'string'],
                'phone' => ['required'],
                'gender' => ['required'],
            ]
        ];
    }

    public function render(): View
    {
        return view('livewire.customer.edit-customer');
    }
}
