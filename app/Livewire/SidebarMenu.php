<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class SidebarMenu extends Component
{
    public string $userOrCompanyName = ' ';

    public function mount(): void
    {
        $user = Auth::user();
        $this->userOrCompanyName = Cache::tags([$user->getCacheKey()])
            ->remember(Cache::get($user->getCacheKey()), 60, function() use ($user){
            if($user->hasCompany()){
                return $user->getCompany()->name;
            }else{
                return $user->full_name ;
            }
        });
    }
    public function render()
    {
        return view('livewire.sidebar-menu');
    }
}
