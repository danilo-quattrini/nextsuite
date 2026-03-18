<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Seeder;
use App\Models\Role;

/**
 * The logic is: owner, admin, employee, and viewer get all fields since they're generic roles.
 * manager, accountant,and support are scoped to the fields that are relevant to their function.
 **/

class RoleFieldSeeder extends Seeder
{
    private const string ROLE_FIELD_JSON_PATH = 'data/roles/role_field.json';

    public function run(): void
    {
        $data  = $this->loadData();

        $roles = Role::all()->keyBy('name');
        $fields = Field::all()->keyBy('name');

        foreach ($data as $roleName => $fieldNames) {
            $role = $roles->get($roleName);

            if (!$role) {
                $this->command->warn("Role [{$roleName}] not found, skipping.");
                continue;
            }

            $fieldIds = collect($fieldNames)
                ->map(fn($fieldName) => $fields->get($fieldName)?->id)
                ->filter()
                ->values()
                ->all();

            $role->fields()->sync($fieldIds);
        }
    }

    private function loadData()
    {
        return json_decode(file_get_contents(
            database_path(self::ROLE_FIELD_JSON_PATH)),
            true);
    }
}
