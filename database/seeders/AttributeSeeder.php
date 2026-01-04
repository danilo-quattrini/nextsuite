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
                [
                    'name' => 'weight',
                    'type' => AttributeType::NUMBER,
                ],
                [
                    'name' => 'height',
                    'type' => AttributeType::NUMBER,
                ],
                [
                    'name' => 'eyes color',
                    'type' => AttributeType::STRING,
                    'slug' => 'eyes-color'
                ],
                [
                    'name' => 'blood type',
                    'type' => AttributeType::SELECT,
                    'slug' => 'blood-type',
                    'options' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']
                ],
            ]
        ];
        foreach ($attributeByCategory as $category => $attributes) {
            $category = Category::where('name', '=', $category)->first();

            foreach ($attributes as $attribute) {
                Attribute::create([
                    'name' => $attribute['name'],
                    'type' => $attribute['type'],
                    'slug' => $attribute['slug'] ?? null,
                    'options' => $attribute['options'] ?? null,
                    'category_id' => $category->id
                ]);
            }
        }
    }
}
