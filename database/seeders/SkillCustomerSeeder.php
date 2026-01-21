<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillCustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();

        $skillsByCategory = Skill::with('category')->get()->groupBy(
            fn ($skill) => $skill->category->name
        );

        foreach ($customers as $customer) {
            // Example: assign 2 technical skills
            if (isset($skillsByCategory['Technology']) || isset($skillsByCategory['Abilities'])) {
                $technicalSkills = $skillsByCategory['Technology']->random(5);
                $softSkill = $skillsByCategory['Abilities']->random(10);

                foreach ($technicalSkills as $skill) {
                    $customer->skills()->attach($skill->id, [
                        'level' => rand(1, 5),
                        'years' => rand(0, 10),
                    ]);
                }
                foreach ($softSkill as $skill) {
                    $customer->skills()->attach($skill->id, [
                        'level' => rand(1, 5),
                    ]);
                }
            }
        }
    }
}
