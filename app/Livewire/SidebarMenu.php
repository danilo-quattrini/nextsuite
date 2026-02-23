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
        $this->userOrCompanyName = Cache::remember(User::getCacheKey(), 60, function(){
            if(User::hasCompany(Auth::user()->id)){
                return User::getCompany(Auth::user()->id)->pluck('name');
            }else{
                return Auth::user()->full_name ;
            }
        });
    }
    public function render()
    {
        return view('livewire.sidebar-menu');
    }
}
