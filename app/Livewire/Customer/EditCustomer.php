<?php

namespace App\Livewire\Customer;

use App\Livewire\Forms\CustomerForm;
use App\Models\Customer;
use App\Services\NationalityService;
use App\Traits\WithStep;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Activitylog\Models\Activity;

class EditCustomer extends Component
{
    use WithFileUploads;
    use WithStep;

//    #[Validate('mimes:jpeg,png,jpg,gif', message: 'Customer profile photo should be  one of this formats: jpeg,png,jpg,gif.')]
//    #[Validate('image', message: 'The file must be an image.')]
//    #[Validate('max:2048', message: 'Profile image it\'s too large.')]
    public $customer_photo;

    public array $nationalities = [];



    public Customer $customer;
    public CustomerForm $form;

    public function mount(NationalityService $nationalityService): void
    {

        $this->form->customer_photo = $this->customer->profile_photo_url ?? null;
        $this->form->full_name = $this->customer->full_name;
        $this->form->email = $this->customer->email;
        $this->form->dob = date_format($this->customer->dob, 'Y-m-d');
        $this->nationalities = $nationalityService->all();
        $this->form->nationality = $this->customer->nationality;
        $this->form->phone = $this->customer->phone;
        $this->form->gender = $this->customer->gender;
    }
    public function render()
    {
        return view('livewire.customer.edit-customer');
    }

    public function edit(): void
    {
        $this->validate();

        if($this->customer_photo != null) {
            $imageName = strtolower(str_replace(' ', '_',
                    $this->form->full_name)).'.'.$this->customer_photo->extension();
            $this->customer_photo->storeAs('customers-profile-photos', $imageName, 'public');
        }

        $customer = $this->customer->update([
            'profile_photo_url' => $imageName ?? null,
            'full_name'  => $this->form->full_name,
            'email' => $this->form->email,
            'nationality' => $this->form->nationality,
            'phone' => $this->form->phone,
            'dob'   => $this->form->dob,
            'gender' => $this->form->gender,
        ]);

        Activity::all()->last();

        session()->flash('status', 'Update customer ' . $this->form->full_name . ' successfully.');

        $this->redirect(route('customer.list'), navigate: true);
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
}
