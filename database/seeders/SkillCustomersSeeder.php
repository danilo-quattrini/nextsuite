<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillCustomersSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();

        $skillsByType = Skill::with('category')->get()->groupBy(
            fn ($skill) => $skill->category->type
        );

        foreach ($customers as $customer) {
            // Example: assign 2 technical skills
            if (isset($skillsByType['technology']) || isset($skillsByType['soft_skill'])) {
                $technicalSkills = $skillsByType['technology']->random(5);

                $softSkill = $skillsByType['soft_skill']->all();

                foreach ($technicalSkills as $skill) {
                    $customer->skills()->attach($skill->id, [
                        'level' => rand(1, 5),
                        'years' => rand(0, 10),
                        'user_id' => $customer->user_id
                    ]);
                }
                foreach ($softSkill as $skill) {
                    $customer->skills()->attach($skill->id, [
                        'level' => rand(1, 100),
                        'user_id' => $customer->user_id
                    ]);
                }
            }
        }
    }
}
