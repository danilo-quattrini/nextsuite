<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = json_decode(
            file_get_contents(database_path('data/field/field.json')),
            true
        );

        foreach ($fields ?? [] as $name) {
            Field::firstOrCreate(['name' => $name]);
        }
    }
}
