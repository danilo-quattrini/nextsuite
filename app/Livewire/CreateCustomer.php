<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCustomer extends Component
{
    use WithFileUploads;
    public $customer_photo;
    public string $full_name;
    public string $email;

    public function render()
    {
        return view('livewire.create-customer');
    }
}
