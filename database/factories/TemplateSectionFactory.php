<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TemplateSection>
 */
class TemplateSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'user_info',
            'section_type' => 'personal_info',
            'order' => 1,
            'is_required' => true,
            'data_source' => str(Customer::class),
            'template_id' => Template::factory(),
            'formatting_rules' => json_encode(['key' => 'value']),
            'config' => [
                'fields' => [
                    ['key' => 'full_name', 'label' => 'Full Name', 'required' => true],
                    ['key' => 'email', 'label' => 'Email', 'required' => true],
                    ['key' => 'phone', 'label' => 'Phone', 'required' => false],
                    ['key' => 'dob', 'label' => 'Date of Birthday', 'required' => false],
                    ['key' => 'gender', 'label' => 'Gender', 'required' => false],
                    ['key' => 'nationality', 'label' => 'Nationality', 'required' => false],
                ],
                'layout' => 'two_column'
            ]
        ];
    }
}
