<?php

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Models\Category;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    private const string SKILL_JSON_PATH = 'data/skill/skills.json';
    private const string SOFT_SKILL_TYPE = 'soft_skill';

    public function run(): void
    {
        $skillsByCategory = $this->loadSkillsData();
        foreach ($skillsByCategory as $categoryName => $skills) {
            $category = Category::where('name', $categoryName)->first();
            if (! $category) {
                continue;
            }
            $this->processSkills($skills, $category);
        }
    }


    private function hasSubcategory(array $skillData): bool
    {
        return isset($skillData['subcategory']);
    }

    private function createSkill(
        string $name,
        int $categoryId,
        ?string $description,
    ): void
    {
        Skill::firstOrCreate([
            'name' => $name,
            'category_id' => $categoryId,
        ], [
            'description' => $description ?? null,
        ]);
    }

    /**
     * @return mixed
     */
    private function loadSkillsData(): array
    {
        return json_decode(
            file_get_contents(database_path(self::SKILL_JSON_PATH)),
            true
        );
    }

    /**
     * @param  mixed  $skillData
     * @return void
     */
    private function handleSubCategory(mixed $skillData): void
    {
        $subcategory = Category::firstOrCreate([
            'name' => $skillData['subcategory'],
            'type' => $skillData['type'],
        ]);

        foreach ($skillData['skills'] as $subCategorySkills) {
            $this->createSkill(
                $subCategorySkills['name'],
                $subcategory->id,
                $subCategorySkills['description'] ?? null
            );
        }
    }

    /**
     * @param  mixed  $skills
     * @param  Category  $category
     * @return void
     */
    private function processSkills(mixed $skills, Category $category): void
    {
        foreach ($skills as $skillData) {
            if ($this->hasSubcategory($skillData)) {
                $this->handleSubCategory($skillData);
            } else {
                $this->createSkill(
                    $skillData['name'],
                    $category->id,
                    $skillData['description'] ?? null
                );
            }
        }
    }
}
