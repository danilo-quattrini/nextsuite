<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'Gym',
            'Nutrition',
            'Human Resources',
            'Artificial Intelligence',
            'Software',
            'Healthcare',
            'Legal',
            'Education',
            'Logistics',
            'Finance',
            'Marketing',
            'Construction',
            'Agriculture',
            'Retail',
            'Hospitality',
        ];

        foreach ($names as $name) {
            Field::firstOrCreate(['name' => $name]);
        }
    }
}
