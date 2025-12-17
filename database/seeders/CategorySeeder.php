<?php

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * @var array<string, array<CategoryType::*>>
         */
        $fieldWithCategory = [
            [
                'name' => 'Health',
                'type' => CategoryType::HEALTH,
                'fields' => [
                    'Gym',
                    'Nutrition',
                    'Healthcare',
                    'Hospitality',
                ],
            ],
            [
                'name' => 'Technology',
                'type' => CategoryType::TECHNOLOGY,
                'fields' => [
                    'Artificial Intelligence',
                    'Software',
                ],
            ],
            [
                'name' => 'Business',
                'type' => CategoryType::BUSINESS,
                'fields' => [
                    'Finance',
                    'Marketing',
                    'Human Resources',
                    'Hospitality',
                ],
            ],
            [
                'name' => 'Languages',
                'type' => CategoryType::PROFESSIONAL_SERVICES,
                'fields' => [
                    'Education',
                ],
            ],
        ];

        foreach ($fieldWithCategory as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                ['type' => $category['type']->value]
            );
        }
    }
}
