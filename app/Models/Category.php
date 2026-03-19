<?php

namespace App\Models;

use App\Enums\CategoryType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    private const string CACHE_KEY = 'category';
    private const int CACHE_TTL = 3600;

    protected $fillable = [
        'name',
        'type',
    ];
    protected $casts = [
        'type' => CategoryType::class,
    ];


    protected static function booted(): void
    {
        $clearCache = fn() => self::clearAllCaches();

        static::created($clearCache);
        static::updated($clearCache);
        static::deleted($clearCache);
    }

    protected static function clearAllCaches(): void
    {
        Cache::tags([self::CACHE_KEY])->flush();
    }

    // ==================== RELATIONSHIPS ====================
    
    public function fields(): BelongsToMany
    {
        return $this->belongsToMany(Field::class, 'field_category', 'category_id', 'field_id');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }

    // ==================== QUERY METHODS (FIXED) ====================

    /**
     * Get all the attributes.
     * @param  int|null  $key
     * @return Collection
     */
    public static function getAttributesWithCategoryId(?int $key = null): Collection
    {
        if ($key === null) {
            return collect();
        }

        return Attribute::with('category')
            ->where('category_id', $key)
            ->get();
    }

    /**
     * Load all the categories
     * @return Collection
     * */
    public static function getAllExceptSoftSkills(): Collection
    {
        $key = self::CACHE_KEY . ':all_except_soft_skills';
        return Cache::tags([self::CACHE_KEY])->remember($key, self::CACHE_TTL, function () {
            return static::with(['attributes', 'fields'])
                ->where('type' , '<>', CategoryType::SOFT_SKILL)
                ->get();
            }
        );
    }

    /**
     * Get categories by type
     * @param  CategoryType  $type
     * @return Collection
     */
    public static function getByType(CategoryType $type): Collection
    {
        $key = self::CACHE_KEY . ':type_' . $type->value;

        return Cache::tags([self::CACHE_KEY])->remember($key, self::CACHE_TTL, function () use ($type) {
            return static::with(['attributes', 'fields'])
                ->where('type', $type)
                ->get();
        });
    }
}
