<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Customer;

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

    /**
     * Find customers that have all the selected skills.
     */
    public static function findCustomerWithSkills(
        array $skillIds,
        int $perPage = 6
    ): LengthAwarePaginator {
        $skillIds = array_values(array_unique(array_filter($skillIds)));

        if (empty($skillIds)) {
            return Customer::getCustomersWithReviews();
        }

        return Customer::query()
            ->with('skills')
            ->withCount('reviews as reviews_count')
            ->withAvg('reviews as reviews_avg_rating', 'rating')
            ->whereIn('id', function ($query) use ($skillIds) {
                $query->select('customer_id')
                    ->from('skill_customers')
                    ->whereIn('skill_id', $skillIds)
                    ->groupBy('customer_id')
                    ->havingRaw('COUNT(DISTINCT skill_id) = ?', [count($skillIds)]);
            })
            ->paginate($perPage);
    }

    public function isSoftSkill(): bool
    {
        return $this->category->type->value === 'soft_skill';
    }
}
