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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Model implements SkillAssignable, AttributeAssignable
{
    use HasFactory, SoftDeletes, LogsActivity;

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
        // Clear cache when customer is created
        static::created(function () {
            self::clearAllContexts();
        });

        // Clear cache when customer is updated
        static::updated(function () {
            self::clearAllContexts();
        });

        // Clear cache when customer is deleted
        static::deleted(function () {
            self::clearAllContexts();
        });
    }

    protected static function clearAllContexts(): void
    {
        Cache::tags([self::CACHE_KEY])->flush();
    }

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


    public function skillSchema(): MorphMany
    {
        return $this->morphMany(SkillSchema::class, 'assignable');
    }

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
        if (!$this->relationLoaded('skills')) {
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
        if(self::hasSkill()) {
            return $this->loadSkills()
                ->map(fn($skill) => [
                    'name' => $skill->name,
                    'level' => $skill->pivot->level,
                    'type' => $skill->category->type->value
                ]);
        }
        return  collect();
    }

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['full_name', 'email', 'company_id', 'user_id']);
    }

    public static function getCustomersOwnedByUser(?string $context = null)
    {
        $page = request()->get('page', 1);

        if ($context === null) {
            $currentRoute = Route::currentRouteName();
            $context = $currentRoute ?: 'default';
        }

        $key = $context . ':' . $page;
        return Cache::tags([self::CACHE_KEY])->remember($key, self::CACHE_TTL, function () {
            return static::with('user')
                ->where('user_id', Auth::user()->id)
                ->paginate(6);
        });
    }
    public static function getCustomersWithReviews(?string $context = null)
    {
        $page = request()->get('page', 1);

        if ($context === null) {
            $currentRoute = Route::currentRouteName();
            $context = $currentRoute ?: 'default';
        }

        $key = $context . ':' . $page;

        return Cache::tags([self::CACHE_KEY])->remember($key, self::CACHE_TTL, function () {
            return static::with('skills')
                ->withCount([
                    'reviews as reviews_count' => function ($query) {
                        $query->where('reviewable_type', 'App\Models\Customer');
                }
                ])
                ->withAvg([
                    'reviews as reviews_avg_rating' => function($query) {
                        $query->where('reviewable_type', 'App\Models\Customer');
                    }
                ], 'rating')
                ->paginate(6);
        });

    }
}
