<?php

namespace App\Livewire;

use App\Domain\Role\Services\RoleService;
use App\Models\Company;
use App\Models\Field;
use App\Models\User;
use App\Notifications\Services\NotificationService;
use App\Traits\ArrayOperation;
use App\Traits\WithStep;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CreateCompany extends Component
{
    use WithFileUploads;
    use WithStep;
    use WithPagination;
    use ArrayOperation;

    public string $name;
    public ?string $website = null;
    public ?string $email = null;
    public ?string $vat_number = null;
    public ?string $address_line = null;
    public ?string $city = null;
    public string $phone =  '';
    public $company_photo;

    public $fields;
    public ?int $owner_id = null;

    public array $selectedFields = [];
    public array $selectedRows = [];

    public function mount(): void
    {
        $this->fields = Field::getFields();
    }

    public function render()
    {
        return view('livewire.create-company');
    }

    // ==== TABLE OPERATION ===

    /**
     * Method that maps each column of the table we are going to create with this format.
     * ```
     *   [
     *      'key' => 'full_name',
     *      'label' => 'User',
     *      'icon' => 'user',
     *      'visible' => true,
     *      'hiddenOnMobile' => false,
     *   ]
     * ```
     * @return array for the table
     * */
    #[Computed]
    public function tableColumns(): array
    {
        return [
            [
                'key' => 'full_name',
                'label' => 'User',
                'icon' => 'user',
                'visible' => true,
                'hiddenOnMobile' => false,
            ],
            [
                'key' => 'email',
                'label' => 'Email',
                'icon' => 'envelope',
                'visible' => true,
                'hiddenOnMobile' => false,
            ],
            [
                'key' => 'id',
                'label' => 'Invite',
                'icon' => 'user-plus',
                'type' => 'checkbox',
                'visible' => true,
                'hiddenOnMobile' => false,
            ]
        ];
    }

    /**
     * Get all the users from the database
     * @return  LengthAwarePaginator pagination of the users in the current page.
     * */
    #[Computed(cache: false)]
    public function users(): LengthAwarePaginator
    {
        return User::getUsers(page: $this->getPage());
    }

    // ==== FIELD OPERATION ====
    public function selectField(int $fieldId): void
    {
        $this->toggleItem(
            targetProperty: 'selectedFields',
            key: $fieldId,
            sourceProperty: 'fields'
        );
    }

    #[On('tag-dismissed')]
    public function removeSelectedField($value): void
    {
        $id = $this->fields->whereIn('name', $value)->first()->id;
        $this->removeArrayItem(
            property: 'selectedFields',
            key: $id,
       );
    }

    // ====== VALIDATION OPERATION =====
    /**
     * Validation rules
     */
    protected function rules(): array
    {
        return [
            'company_photo' => 'nullable|image|max:2048',
            'name' => 'required|min:5|max:255|unique:companies',
            'website' => 'nullable|url|max:255',
            'email' => 'required|email|max:255',
            'vat_number' => 'string|max:255',
            'address_line' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'selectedFields' => 'required|min:1',
            'selectedRows'   => 'required|array|min:1',
            'owner_id' => 'nullable|exists:users,id',
        ];
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return [
            'selectedRows.required' => 'You should select at least one user for your company, it\'s required .',
            'selectedRows.min'      => 'You should select at least one user for your company, minimum 1.',
        ];
    }

    /**
     * Array of each step with their relative rules
     */
    protected function stepRules(): array
    {
        return  [
            1 => [
                'company_photo' => 'image|max:2048|mimes:jpeg,png,jpg,webp',
                'name' => 'required|min:5|max:255|unique:companies',
                'website' => 'nullable|url|max:255',
                'email' => 'required|email|max:255|unique:companies',
                'vat_number' => 'required|string|max:255',
            ],

            2 => [
                'address_line' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'phone' => 'required|string|max:50',
                'selectedFields' => 'required|min:1',
            ]
        ];
    }

    protected function stepValidationMessages(): array
    {
        return [
            1 => [
                'company_photo.mimes' => 'Photo must be jpeg, png, jpg or webp.',
                'name.required' => 'Please enter the company name.',
                'name.min'      => 'Company name must be at least 5 characters.',
                'name.max'      => 'Company name should not be more than 255 characters.',
                'email.required'     => 'An email address for this company is required.',
                'email.unique'       => 'This company email is already registered.',
                'website.url'     => 'Enter a valid URL for this company.',
                'vat_number.required'       => 'VAT is required.'
            ],
            2 => [
                'city.required'       => 'The city of the company is required.',
                'city.string'         => 'You should write a city for your company',
                'address_line.required'        => 'The address line of the company is required.',
                'address_line.string'        => 'You should write an address line for your company.',
                'phone.required'      => 'Please add a phone number for this company.',
                'phone.string'        => 'Phone should be into a valid format'
            ]
        ];
    }

    // ===== SUBMIT OPERATION ====
    public function submit(): void
    {
        $selectedUserId = array_keys(array_filter($this->selectedRows));

        $validated = $this->validate();


        if($this->company_photo) {
            $imageName = strtolower(str_replace(' ', '_', $this->name)).'.'.$this->company_photo->extension();
            $this->company_photo->storeAs('company-profile-photos', $imageName, 'public');
        }

        $userId = $validated['owner_id'] ?? auth()->id();

        $company = Company::create([
            'name' => $validated['name'],
            'website' => $validated['website'] ?? null,
            'email' => $validated['email'],
            'vat_number' => $validated['vat_number'],
            'address_line' => $validated['address_line'] ?? null,
            'city' => $validated['city'] ?? null,
            'phone' => $validated['phone'],
            'company_photo' => $imageName ?? null,
            'owner_id' => $userId,
        ]);


        $company->fields()->sync(
            array_keys($this->selectedFields)
        );

        $this->sendInvitation($company, $selectedUserId);

        $this->setOwnerRole();

        $this->redirect(
            auth()->check()
                ? route('company.show')
                : route('login')
        );
    }

    /**
     * Send invitation to all users that has been selected,
     * during the process of the company creation.
     *
     * @param  Company  $company that has been created.
     * @param  array  $selectedUserId user ids have been selected during the
     * creation.
     * */
    private function sendInvitation(
        Company $company,
        array $selectedUserId
    ): void {

        if(empty($selectedUserId))
            return;

        $users = User::find($selectedUserId);

        if($users->isNotEmpty()){
            app(NotificationService::class)->sendBulk(
                notifiables: $users,
                subject: "Receive and invitation from the company {$company->name}",
                message: "Hi dear you have been invited to join in the company {$company->name}",
                actionText: "Join",
                actionUrl: "",
                channels: ['mail', 'database']
            );
        }
    }
    /**
     * Set the role of the authenticated user from
     * 'guest' or any type of role different from 'owner'
     * to 'owner'.
     *
     * After the role has been synced, it will call
     * the RoleService instance to invalid the cache
     * of the old role, then it will dispatch an event
     * to refresh
     * */
    private function setOwnerRole(): void
    {
        $user = Auth::user();

        if(!$user->hasRole('owner'))
        {
            $user->syncRoles(['owner']);
            app(RoleService::class)->invalidateUserCache($user);
            $this->dispatch('refresh-navigation-menu');
        }
    }
}
