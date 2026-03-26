<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class SidebarMenu extends Component
{
    public string $userOrCompanyName = ' ';
    public bool $hasCompany = false;

    public function mount(): void
    {
        $user = Auth::user();
        $this->hasCompany = $user->hasCompany();
        $this->userOrCompanyName = $this->getUserOrCompanyName($user);
    }
    public function render()
    {
        return view('livewire.sidebar-menu');
    }

    public function getUserOrCompanyName(User|Authenticatable|null $user): mixed
    {
        return Cache::tags([$user->getCacheKey()])
            ->remember(Cache::get($user->getCacheKey()), 60, function () use ($user) {
                if ($user->hasCompany()) {
                    return $user->getCompany()->name;
                } else {
                    return $user->full_name;
                }
            });
    }
}
