<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\TemplateSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TemplateSection::factory(1)->create();
    }
}
