<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    private const string ROLE_PERMISSION_JSON_PATH = 'data/permission/role_permissions.json';

    public function run(): void
    {
        $data = $this->loadData();

        $roles       = Role::all()->keyBy('name');
        $permissions = Permission::all()->keyBy('name');

        foreach ($data as $roleName => $permissionNames) {
            $role = $roles->get($roleName);

            if (!$role) {
                $this->command->warn("Role [{$roleName}] not found, skipping.");
                continue;
            }

            $permissionIds = collect($permissionNames)
                ->map(fn($name) => $permissions->get($name)?->id)
                ->filter()
                ->values()
                ->all();

            $role->syncPermissions($permissionIds);
        }
    }

    private function loadData()
    {
        return json_decode(
            file_get_contents(database_path(self::ROLE_PERMISSION_JSON_PATH)),
            true
        );
    }
}
