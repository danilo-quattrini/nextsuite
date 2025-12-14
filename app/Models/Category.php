<?php

namespace App\Models;

use App\Enums\CategoryType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    protected $fillable = ['name'];
    protected $casts = [
        'type' => CategoryType::class,
    ];
    public function fields(): BelongsToMany
    {
        return $this->belongsToMany(Field::class, 'field_category', 'category_id', 'field_id');
    }
}
