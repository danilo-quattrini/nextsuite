<?php

namespace App\Http\Livewire;

use App\Domain\Role\Services\RoleService;
use Auth;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class NavigationMenu extends Component
{
    private RoleService $roleService;

    /**
     * The component's listeners.
     *
     * @var array
     */
    protected $listeners = [
        'refresh-navigation-menu' => '$refresh',
    ];
    /**
     * Runs the method on every request in the page
     * */
    public function boot(RoleService $roleService): void
    {
        $this->roleService = $roleService;
    }

    /**
     * Constructor of the Livewire component.
     * */
    public function mount(RoleService $roleService): void
    {
        $this->roleService = $roleService;
    }

    #[Computed(cache: false)]
    public function userRole()
    {
        $roles = $this->roleService->getUserRoleNames(Auth::user());

        return $roles[0] ?: 'No Role';
    }
    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        return view('navigation-menu');
    }
}