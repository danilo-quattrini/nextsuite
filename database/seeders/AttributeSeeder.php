<?php

namespace Database\Seeders;

use App\Enums\AttributeType;
use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $data = json_decode(
            File::get(database_path('data/attributes/customer_attributes.json')),
            true
        );
        foreach ($data as $categoryType => $attributes) {
            $category = Category::where('name', $categoryType)->first();
            foreach ($attributes as $attribute) {
                Attribute::firstOrCreate(
                    ['slug' => $attribute['slug']],
                    [
                        'name' => $attribute['name'],
                        'type' => AttributeType::from($attribute['type']),
                        'options' => $attribute['options'] ?? null,
                        'category_id' => $category->id
                    ]
                );
            }
        }
    }
}
