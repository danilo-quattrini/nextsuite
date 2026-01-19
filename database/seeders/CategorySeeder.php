<?php

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

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
        $fieldWithCategory = json_decode(
            File::get(database_path('data/category/categories.json')),
            true
        );

        foreach ($fieldWithCategory as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                ['type' => $category['type']]
            );
        }
    }
}
