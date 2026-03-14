<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            RoleFieldSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            FieldSeeder::class,
            FieldCategorySeeder::class,
            SkillSeeder::class,
            AttributeSeeder::class,
            CustomerSeeder::class,
            SkillCustomersSeeder::class,
            AttributeCustomerSeeder::class,
            TemplateSeeder::class,
            CustomerRoleSeeder::class
        ]);
    }
}
