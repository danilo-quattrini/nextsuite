<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    private const string PERMISSIONS_JSON_PATH = 'data/permission/permissions.json';

    public function run(): void
    {
        $permissions = $this->loadData();

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name'       => $permission['name'], 'guard_name' => $permission['guard_name']],
                ['description' => $permission['description']]
            );
        }
    }

    private function loadData()
    {
        return json_decode(
            file_get_contents(database_path(self::PERMISSIONS_JSON_PATH)),
            true
        );
    }
}
