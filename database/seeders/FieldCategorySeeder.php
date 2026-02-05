<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Field;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryFieldMap = [
            'Health' => [
                'Gym',
                'Nutrition',
                'Healthcare',
                'Hospitality',
            ],
            'Technology' => [
                'Artificial Intelligence',
                'Software',
            ],
            'Business' => [
                'Finance',
                'Marketing',
                'Human Resources',
                'Hospitality',
            ],
            'Languages' => [
                'Education'
            ]
        ];

        foreach ($categoryFieldMap as $categoryName => $fieldNames) {

            $category = Category::where('name', $categoryName)->first();

            if (! $category) {
                continue;
            }

            $fieldIds = Field::whereIn('name', $fieldNames)->pluck('id');

            $category->fields()->syncWithoutDetaching($fieldIds);
        }
    }
}
