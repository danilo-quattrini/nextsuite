<?php

namespace Database\Factories;

use App\Enums\CategoryType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    public $id;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' =>  fake()->randomElement([
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
            ]),
            'type' => fake()->randomElement(CategoryType::class),
        ];
    }
}
