<?php

namespace App\Livewire\Skill;

use App\Models\Category;
use App\Models\Skill;
use Illuminate\Support\Collection;
use Livewire\Component;

class CreateSkill extends Component
{
    public ?Collection $categories = null;
    public ?Collection $skills = null;

    public $selectedCategory = null;
    public $selectedSkill = null;

    public function mount(): void
    {
        $this->categories = Category::where('type', 'soft_skill')
            ->where('name', '<>', 'Abilities')
            ->get();

        $this->skills = collect();
    }

    public function updatedSelectedCategory($categoryId): void
    {
        if(!empty($categoryId)){
            $this->skills = Skill::where('category_id', $categoryId)
                ->get()
                ->map(function ($skill) {
                    return [
                        'id' => $skill->id,
                        'value' => strtolower($skill->name),
                        'label' => $skill->name
                    ];
                });
        }else{
            $this->skills = collect();
        }
        $this->selectedSkill = null;
    }

    public function render()
    {
        return view('livewire.skill.create-skill');
    }
}
