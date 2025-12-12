<?php

namespace App\Livewire;

use App\Models\Company;
use App\Models\Field;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCompany extends Component
{
    use WithFileUploads;

    public string $name;
    public ?int $employees = null;
    public string $phone =  '';
    public $business_photo;
    public $fields;
    public ?int $field = null;
    public ?int $owner_id = null;

    public function mount()
    {
        $this->fields = Field::all(); // assign values
    }

    public function render()
    {
        return view('livewire.create-company');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|min:5|max:255',
            'employees' => 'required|integer',
            'phone' => 'required|string',
            'business_photo' => 'required|image|max:2048',
            'field' => 'required|exists:fields,id',
            'owner_id' => 'nullable|exists:users,id',
        ];
    }

    public function submit()
    {
        $validated = $this->validate();

        $imageName = strtolower(str_replace(' ', '_', $this->name)) . '.' . $this->business_photo->extension();
        $this->business_photo->storeAs('business-profile-photos', $imageName, 'public');

        $userId = $validated['owner_id'] ?? auth()->id();

        Company::create([
            'name' => $validated['name'],
            'employees' => $validated['employees'],
            'phone' => $validated['phone'],
            'business_photo' => $imageName,
            'field_id' => $validated['field'],
            'owner_id' => $userId,
        ]);

        $this->redirect('/');
    }
}
