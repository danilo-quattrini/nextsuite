<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    private const string ROLE_JSON_PATH = 'data/roles/roles.json';

    public function run(): void
    {
        $roles = $this->loadRolesData();
        $this->createRole($roles);
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
}
