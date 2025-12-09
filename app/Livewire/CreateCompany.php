<?php

namespace App\Livewire;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCompany extends Component
{
    use WithFileUploads;

    public $name;
    public $employees;
    public $phone;
    public $business_photo;

    public function render()
    {
        return view('livewire.create-company');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|min:5|max:255',
            'employees' => 'required|integer',
            'phone' => 'required',
            'business_photo' => 'required|image|max:2048',
        ];
    }

    public function submit()
    {
        $validated = $this->validate();

        $imageName = strtolower(str_replace(' ', '_', $this->name)) . '.' . $this->business_photo->extension();
        $this->business_photo->storeAs('business-profile-photos', $imageName, 'public');

        Company::create([
            'name' => $this->name,
            'employees' => $this->employees,
            'phone' => $this->phone,
            'business_photo' => $imageName,
        ]);

        $this->redirect('/');
    }
}
