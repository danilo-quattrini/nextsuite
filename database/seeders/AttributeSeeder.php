<?php

namespace Database\Seeders;

use App\Enums\AttributeType;
use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributeByCategory = [
            'Health' => [
                ['name' => 'weight', 'type' => AttributeType::NUMBER, 'slug' => null],
                ['name' => 'height', 'type' => AttributeType::NUMBER, 'slug' => null],
                ['name' => 'age', 'type' => AttributeType::NUMBER, 'slug' => null],
            ]
        ];
        foreach ($attributeByCategory as $category => $attributes) {
            $category = Category::where('name', '=', $category)->first();

            foreach ($attributes as $attribute) {
                Attribute::create([
                    'name' => $attribute['name'],
                    'type' => $attribute['type'],
                    'slug' => $attribute['slug'],
                    'category_id' => $category->id
                ]);
            }
        }
    }
}
