<?php

namespace Database\Factories;

use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Template>
 */
class TemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'type' => DocumentType::PDF,
            'category' => fake()->randomElement(['curriculum', 'report', 'blank']),
            'structure' => json_encode(['field' => 'value']),
            'settings' => json_encode(['settings' => 'value']),
            'blade_template' => null,
            'is_active' => true,
        ];
    }
}
