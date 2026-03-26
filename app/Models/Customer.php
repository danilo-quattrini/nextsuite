<?php

namespace App\Models;

use App\Domain\Attribute\Contracts\AttributeAssignable;
use App\Domain\Skill\Contracts\SkillAssignable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Customer extends Model implements SkillAssignable, AttributeAssignable
{
    use HasFactory, HasRoles, SoftDeletes, LogsActivity;

    protected string $guard_name = 'web';
    private const string CACHE_KEY = 'customers';
    private const int CACHE_TTL = 3600;

    protected $fillable = [
        'profile_photo_url',
        'full_name',
        'email',
        'phone',
        'dob',
        'gender',
        'nationality',
        'company_id',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::created(fn() => self::clearAllContexts());
        static::updated(fn() => self::clearAllContexts());
        static::deleted(fn() => self::clearAllContexts());
    }

    // ==================== CACHE OPERATION ====================

    protected static function clearAllContexts(): void
    {
        Cache::tags([self::CACHE_KEY])->flush();
    }


    /**
     * Clear cache for a specific customer
     * Called by Review model when review is added/updated/deleted
     */
    public static function clearModelCache(
        ?int $id = null
    ): void
    {
        if(!$id){
            return;
        }
        $keys = [
            self::CACHE_KEY . ':' . $id . ':with_reviews',
            self::CACHE_KEY . ':' . $id . ':with_review_stats',
            self::CACHE_KEY . ':' . $id . ':details',
        ];

        foreach ($keys as $key){
            Cache::tags([self::CACHE_KEY])->forget($key);
        }

        self::clearAllContexts();
    }
    // ==================== RELATIONSHIPS ====================

    /**
     * Get the skills from the Skill model.
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'skill_customers', 'customer_id', 'skill_id')
            ->using(SkillCustomers::class)
            ->withPivot(['years', 'level', 'notes', 'user_id'])
            ->withTimestamps();
    }

    /**
     * Get the attribute polymorph model.
     */
    public function attributes(): MorphToMany
    {
        return $this->morphToMany(Attribute::class, 'attributable', 'attribute_users', 'attributable_id', 'attribute_id')
            ->using(AttributeAssignment::class)
            ->withPivot('value')
            ->withTimestamps()
            ->wherePivotNull('deleted_at');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all the customer's reviews.
     */
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Get all the customer's documents.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Get user skill scheme.
     */
    public function skillSchema(): MorphMany
    {
        return $this->morphMany(SkillSchema::class, 'assignable');
    }

    // ==================== HELPER METHODS ====================

    /**
     * Add a skill to a customer
     * @param  User  $user
     * @param  int  $id
     * @param  int  $level
     * @param  int|null  $years
     */
    public function addSkill(
        User $user,
        int $id,
        int $level,
        int|null $years
    ): void {

        $skill = Skill::findOrFail($id);

        if ($skill->isSoftSkill()) {
            $skillCustomer = SkillCustomers::findOrCreateSkill($skill, $this);
            $skillCustomer->addEvaluation($user, $level);
        }else
        {
            $this->skills()->syncWithoutDetaching([
                $id => [
                    'level' => $level,
                    'years' => $years,
                    'user_id' => $user->id,
                ]
            ]);
        }
    }

    /**
     * Remove a skill with a specific id from the customer
     */
    public function removeSkill(int $skillId): void
    {
        $skill = Skill::findOrFail($skillId);

        if ($skill->isSoftSkill()) {
            SkillCustomers::where('customer_id', $this->id)
                ->where('skill_id', $skillId)
                ->delete();
        } else {
            $this->skills()->detach($skillId);
        }
    }

    /**
     * Check if customer has a specific skill assigned
     */
    public function skillExists(int $skillId): bool
    {
        return $this->skills()->where('skill_id', $skillId)->exists();
    }

    /**
     * Check if customer has a relation with the skill model.
     */
    public function hasSkill(): bool
    {
        return $this->skills()->exists();
    }

    /**
     * Get skills grouped by category
     */
    public function skillsByCategory(): Collection
    {
        if (!$this->relationLoaded('skills.category')) {
            $this->load(['skills.category']);
        }

        return $this->skills
            ->filter(fn($skill) => $skill->category !== null)
            ->groupBy('category.name');
    }

    /**
     * Get all the skill owned from the customer
     */
    public function getSkills(): Collection
    {
        if (!$this->relationLoaded('skills.category')) {
            $this->load(['skills.category']);
        }

        if ($this->skills->isEmpty()) {
            return collect();
        }

        return $this->skills->map(fn($skill) => [
            'name' => $skill->name,
            'level' => $skill->pivot->level,
            'type' => $skill->category?->type?->value
        ]);
    }

    // ====== ATTRIBUTE OPERATION =====

    /**
     * Add an attribute to a customer, syncWithoutDetaching = remove already existing record and add new ones.
     */
    public function addAttribute(Attribute $attribute, mixed $value): void
    {
        $this->attributes()->syncWithoutDetaching([
            $attribute->id => [
                'value' => $value
            ]
        ]);
    }

    /**
     * Get the attribute owned by the customer though the id.
     * @param  int  $key
     * @return Attribute|null
     */
    public function getAssignableAttribute(int $key): ?Attribute
    {
        return $this->attributes()
            ->wherePivot('attribute_id', $key)
            ->first();
    }

    /**
     * Get all the attributest owned
     * @return Collection of attributes
    **/
    public function getAssignableAttributes(): Collection
    {
        if (!$this->relationLoaded('attributes.category')) {
            $this->load(['attributes.category']);
        }

        $attributes = $this->getRelation('attributes');

        if (!$attributes || $attributes->isEmpty()) {
            return collect();
        }

        return $attributes->map(fn($attribute) => [
            'name' => $attribute->name,
            'value' => $attribute->pivot->value,
            'type' => $attribute->category?->type?->value
        ]);
    }

    // ====== HEAVY OPERATION =====
    /**
     * Find the customer with reviews and save them inside the cache
     * @param  int  $id
     * @return Customer|null
     */
    public static function findCustomerWithReview(
        int $id
    ): ?Customer {
        $key = self::CACHE_KEY . ':' . $id . ':with_reviews';

        return Cache::tags([self::CACHE_KEY])->remember($key, self::CACHE_TTL, function () use ($id) {
            return static::with(['reviews.author'], ['skills'])
                ->withCount('reviews as reviews_count')
                ->withAvg('reviews as reviews_avg_rating', 'rating')
                ->findOrFail($id);
        });
    }

    /**
     * Find the customer with reviews greater than a value and skills
     * @param string $role
     * @param array $skillIds
     * @param  int  $ratingStars
     * @param  int $perPage
     * @return LengthAwarePaginator
     */
    public static function findCustomerWithSkillsAndReviews(
        string $role,
        array $skillIds,
        int $ratingStars = 0,
        int $perPage = 6
    ): LengthAwarePaginator {

        $skillIds = array_values(array_unique(array_filter($skillIds)));

        $query = static::with(['reviews.author', 'skills', 'roles'])
            ->withCount('reviews as reviews_count')
            ->withAvg('reviews as reviews_avg_rating', 'rating')
            ->groupBy('customers.id');

        if (!empty($role)) {
            $query->role($role);
        }

        if (!empty($skillIds)) {
            $query->whereIn('id', function ($sub) use ($skillIds) {
                $sub->select('customer_id')
                    ->from('skill_customers')
                    ->whereIn('skill_id', $skillIds)
                    ->groupBy('customer_id')
                    ->havingRaw('COUNT(DISTINCT skill_id) = ?', [count($skillIds)]);
            });
        }

        if ($ratingStars > 0) {
            $query->havingRaw('reviews_avg_rating >= ?', [$ratingStars]);
        }

        return $query
            ->orderByRaw('reviews_avg_rating IS NULL ASC, reviews_avg_rating DESC')
            ->paginate($perPage);
    }

    /**
     * Get all the customers owned by a user with saved them inside the cache
     */
    public static function getCustomersOwnedByUser(
        ?string $context = null,
        int $page = 1
    ): LengthAwarePaginator {

        $context = $context ?? Route::currentRouteName() ?? 'default';

        $userId = Auth::id();

        $key = self::CACHE_KEY . ':owned:' . $userId . ':' . $context . ':page:' . $page;

        return Cache::tags([self::CACHE_KEY])->remember($key, self::CACHE_TTL, function () use ($userId, $page) {
            return static::with('user')
                ->where('user_id', $userId)
                ->withCount('reviews as reviews_count')
                ->withAvg('reviews as reviews_avg_rating', 'rating')
                ->paginate(6, ['*'], 'page', $page);
        });
    }

    /**
     * Get all the customers with reviews and their average, save them inside the cache
     */
    public static function getCustomersWithReviews(
        ?string $context = null,
        int $page = 1
    ): LengthAwarePaginator {

        if ($context === null) {
            $currentRoute = Route::currentRouteName();
            $context = $currentRoute ?: 'default';
        }

        $key = self::CACHE_KEY . ':context:' . $context . ':page:' . $page;

        return Cache::tags([self::CACHE_KEY])->remember($key, self::CACHE_TTL, function () use ($page) {
            return static::with('skills')
                ->withCount('reviews as reviews_count')
                ->withAvg('reviews as reviews_avg_rating', 'rating')
                ->paginate(6, ['*'], 'page', $page);
        });

    }
    /**
     * Default method to log specific customer details
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['full_name', 'email', 'company_id', 'user_id']);
    }
}
