<?php

namespace App\Livewire;

use App\Models\Company;
use App\Models\Field;
use App\Traits\ArrayOperation;
use App\Traits\WithStep;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCompany extends Component
{
    use WithFileUploads;
    use WithStep;
    use ArrayOperation;

    public bool $showFieldDropdown = false;

    public string $name;
    public ?string $trade_name = null;
    public ?string $website = null;
    public ?string $email = null;
    public ?string $vat_number = null;
    public ?string $address_line = null;
    public ?string $city = null;
    public ?string $postal_code = null;
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

    protected function rules(): array
    {
        return [
            // 'business_photo' => 'image|max:2048',
            'name' => 'required|min:5|max:255|unique:companies',
            'website' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'vat_number' => 'nullable|string|max:255',
            'address_line' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'phone' => 'required|string',
            'fields' => 'required',
            'selectedFields' => 'required|min:1',
            'owner_id' => 'nullable|exists:users,id',
        ];
    }
    protected function stepRules(): array
    {
        return $this->rulesForStep();
    }

    private function rulesForStep(): array
    {

        return  [
            1 => [
                // 'business_photo' => 'image|max:2048',
                'name' => 'required|min:5|max:255|unique:companies',
                'website' => 'nullable|url|max:255',
                'email' => 'nullable|email|max:255',
                'vat_number' => 'nullable|string|max:255',
            ],

            2 => [
                'address_line' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'postal_code' => 'nullable|string|max:255',
                'phone' => 'required|string',
                'selectedFields' => 'required|min:1',
                'owner_id' => 'nullable|exists:users,id',
            ]
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
            'website' => $validated['website'] ?? null,
            'email' => $validated['email'] ?? null,
            'vat_number' => $validated['vat_number'] ?? null,
            'registration_number' => $validated['registration_number'] ?? null,
            'address_line' => $validated['address_line1'] ?? null,
            'city' => $validated['city'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
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
