<?php

namespace App\Livewire;

use App\Models\Company;
use App\Models\Field;
use App\Traits\ArrayOperation;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCompany extends Component
{
    use WithFileUploads;
    use ArrayOperation;

    public bool $showFieldDropdown = false;

    public string $name;
    public ?int $employees = null;
    public string $phone =  '';
    public $business_photo;
    public $fields;
    public array $selectedFields = [];
    public ?int $owner_id = null;

    public function toggleFieldDropdown(): void
    {
        $this->showFieldDropdown = ! $this->showFieldDropdown;
    }

    public function selectField(int $fieldId): void
    {
        $this->toggleItem(
            targetProperty: 'selectedFields',
            key: $fieldId,
            sourceProperty: 'fields'
        );

        $this->showFieldDropdown = false;
    }

    public function mount(): void
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
            'name' => 'required|min:5|max:255|unique:companies',
            'employees' => 'required|integer',
            'phone' => 'required|string',
            'business_photo' => 'required|image|max:2048',
            'selectedFields' => 'required|min:1',
            'owner_id' => 'nullable|exists:users,id',
        ];
    }

    public function submit(): void
    {
        $validated = $this->validate();

        $imageName = strtolower(str_replace(' ', '_', $this->name)) . '.' . $this->business_photo->extension();
        $this->business_photo->storeAs('business-profile-photos', $imageName, 'public');

        $userId = $validated['owner_id'] ?? auth()->id();

        $company = Company::create([
            'name' => $validated['name'],
            'employees' => $validated['employees'],
            'phone' => $validated['phone'],
            'business_photo' => $imageName,
            'owner_id' => $userId,
        ]);

        $company->fields()->sync(
            array_keys($this->selectedFields)
        );

        if(auth()->check()) {
            $this->redirect(route('company.show'));
        }else{
            $this->redirect(route('login'));
        }
    }
}
