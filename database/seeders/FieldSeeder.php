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
        Field::factory(4)->create(
            new Sequence(
                [
                    'name' => 'Nutritionist'
                ],
                [
                    'name' => 'Artificial Intelligence'
                ],
                [
                    'name' => 'HR Company'
                ],
                [
                    'name' => 'Gym'
                ]
            )
        );
    }
}
