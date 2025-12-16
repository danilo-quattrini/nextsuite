<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Field;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'business_photo' => $this->faker->name(),
            'name' => $this->faker->name(),
            'employees' => $this->faker->numberBetween(1, 9999),
            'phone' => $this->faker->phoneNumber(),
            'field_id' => Field::factory(),
            'owner_id' => User::factory(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
