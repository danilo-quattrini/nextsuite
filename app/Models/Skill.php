<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category_id'
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the categories from the Category model.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the customers from the Customer model.
     */
    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'skill_customers', 'skill_id', 'customer_id')
            ->using(SkillCustomers::class)
            ->withPivot(['years', 'level', 'notes', 'user_id'])
            ->withTimestamps();
    }

    // ==================== HELPER METHODS ====================

    public function isSoftSkill(): bool
    {
        return $this->category->type->value === 'soft_skill';
    }
}
