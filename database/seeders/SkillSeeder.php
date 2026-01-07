<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skillsByCategory = json_decode(
            File::get(database_path('data/skill/skills.json')),
            true
        );

        foreach ($skillsByCategory as $categoryName => $skills) {
            $category = Category::where('name', $categoryName)->first();

            if (! $category) {
                continue;
            }

            foreach ($skills as $skillData) {
                Skill::firstOrCreate([
                    'name' => $skillData['name'],
                    'category_id' => $category->id,
                ], [
                    'description' => $skillData['description'] ?? null,
                ]);
            }
        }
    }
}
