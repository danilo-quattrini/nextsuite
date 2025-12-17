<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skillsByCategory =[

            'Technology' => [
                ['name' => 'PHP', 'description' => 'PHP programming language'],
                ['name' => 'Laravel', 'description' => 'Laravel framework'],
                ['name' => 'MySQL', 'description' => 'Relational database'],
            ],

            'Business' => [
                ['name' => 'Project Management', 'description' => 'Planning, executing and closing projects, managing timelines, scope and stakeholder communication.'],
                ['name' => 'Financial Analysis', 'description' => 'Analyzing financial data to inform budgeting, forecasting and investment decisions.'],
                ['name' => 'Accounting', 'description' => 'Maintaining financial records, preparing statements and ensuring regulatory compliance.'],
                ['name' => 'Marketing Strategy', 'description' => 'Developing go\-to\-market plans, positioning, and campaigns to drive demand and brand growth.'],
                ['name' => 'Sales', 'description' => 'Prospecting, negotiating and closing deals while managing pipeline and customer relationships.'],
                ['name' => 'Business Development', 'description' => 'Identifying partnerships, new markets and opportunities to grow revenue streams.'],
                ['name' => 'Operations Management', 'description' => 'Optimizing processes, supply chain and day\-to\-day operations to improve efficiency.'],
                ['name' => 'Human Resources', 'description' => 'Talent acquisition, performance management, and employee relations.'],
                ['name' => 'Data Analysis', 'description' => 'Interpreting business data to inform decisions and measure performance.'],
                ['name' => 'Customer Relationship Management', 'description' => 'Managing customer lifecycle, retention strategies, and support processes.'],
            ],

            'Languages' => [
                ['name' => 'English'],
                ['name' => 'Spanish'],
                ['name' => 'French'],
            ],
        ];

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
