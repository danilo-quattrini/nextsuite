<?php

namespace App\Models;

use App\Enums\CategoryType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
    ];
    protected $casts = [
        'type' => CategoryType::class,
    ];
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

    /**
     * Get all the attributes.
     * @param  int|null  $key
     * @return Collection
     */
    public static function getAttributesWithCategoryId(?int $key = null): Collection
    {
        return Attribute::with('category')
            ->where('category_id', $key)
            ->get();
    }

    /**
     * Load all the categories
     * */
    public function loadCategories(): Collection
    {
        $key = 'list';
        return Cache::tags([self::CACHE_KEY])->remember($key, self::CACHE_TTL, function () {
            return $this->with('attributes.category.fields')
                ->where('type' , '<>', 'soft_skill')
                ->get();
            }
        );
    }
}
