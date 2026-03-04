<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CustomerRoleSeeder extends Seeder
{
    private const string ROLE_JSON_PATH = 'data/roles/roles.json';

    public function run(): void
    {
        $roles = $this->loadRolesData();
        $this->createRole($roles);
        $this->assignRoleToCustomer($roles);
    }

    private function loadRolesData()
    {
        return json_decode(file_get_contents(database_path(self::ROLE_JSON_PATH)), true);
    }

    public function createRole(array $roles): void
    {
        foreach ($roles as $role) {
            Role::create($role);
        }
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
