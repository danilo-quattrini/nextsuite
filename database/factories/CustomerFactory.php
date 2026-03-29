<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use App\Services\NationalityService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(
    ): array
    {
        $code    = $this->faker->countryCode();
        $country = collect(countries())->get(strtolower($code));
        $name    = $country['name'] ?? $code;

        return [
            'profile_photo_url' => null,
            'full_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'dob' => Carbon::now(),
            'nationality' => $name,
            'nationality_iso' => strtolower($code),
            'gender' => $this->faker->randomElement(['man', 'woman', 'other']),
            'company_id' => Company::factory(),
            'user_id' => User::factory(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
