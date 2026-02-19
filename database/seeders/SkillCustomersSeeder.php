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
            if (isset($skillsByType['technology']) || isset($skillsByType['soft_skill']) || isset($skillsByType['business'])) {

                $technicalSkills = $skillsByType['technology']->random(5);
                $businessSkills = $skillsByType['business']->random(5);
                $softSkill = $skillsByType['soft_skill']->all();

                $this->attachSkill($technicalSkills, $customer);
                $this->attachSkill($businessSkills, $customer);

                $levels = array(25, 50, 75, 100);
                foreach ($softSkill as $skill) {
                    $customer->skills()->attach($skill->id, [
                        'level' => $levels[array_rand($levels)],
                        'user_id' => $customer->user_id
                    ]);
                }
            }
        }
    }

    private function attachSkill($skills, $customer): void
    {
        $levels = array(25, 50, 75, 100);

        foreach ($skills as $skill) {
            $customer->skills()->attach($skill->id, [
                'level' => $levels[array_rand($levels)],
                'years' => rand(1, 30),
                'user_id' => $customer->user_id
            ]);
        }
    }
}
