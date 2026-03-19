<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerRoleSeeder extends Seeder
{
    private const string ROLE_JSON_PATH = 'data/roles/roles.json';

    public function run(): void
    {
        $roles = json_decode(file_get_contents(database_path(self::ROLE_JSON_PATH)), true);
        $this->assignRoleToCustomer($roles);
    }

    private function assignRoleToCustomer(array $roles): void
    {
      Customer::with('roles')->each(
          function ($customer) use ($roles){
              $customer->assignRole($roles[rand(0, sizeof($roles) - 1)]['name']);
          }
      );
    }
}
