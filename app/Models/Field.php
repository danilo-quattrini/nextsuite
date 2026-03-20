<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Field extends Model
{
    /** @use HasFactory<\Database\Factories\FieldFactory> */
    use HasFactory;

    protected $fillable = ['name'];

    private const string CACHE_KEY = 'fields';
    private const int CACHE_TTL = 3600;

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_field', 'field_id', 'company_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'field_category', 'field_id', 'category_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_field', 'field_id', 'role_id');
    }

    // ===== CACHE OPERATION ====

    /**
     * Method that returns a Collection of all
     * the fields that the system contains and save
     * them inside the cache.
     *
     * @return Collection of fields
     * */
    public static function getFields(): Collection
    {
        $key = self::CACHE_KEY . ':all';

        return Cache::tags([self::CACHE_KEY])->remember($key, self::CACHE_TTL, function (){
           return static::with('categories')
               ->get();
        });
    }
}
