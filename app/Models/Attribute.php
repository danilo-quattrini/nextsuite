<?php

namespace App\Models;

use App\Enums\AttributeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'category_id',
        'options',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all customers that have this attribute.
     */
    public function customers(): MorphToMany
    {
        return $this->morphToMany(Customer::class, 'attributable', 'attribute_users', 'attribute_id', 'attributable_id')
            ->using(AttributeAssignment::class)
            ->withPivot('value')
            ->withTimestamps()
            ->wherePivotNull('deleted_at');
    }

    /**
     * Get all users that have this attribute.
     */
    public function users(): MorphToMany
    {
        return $this->morphToMany(User::class, 'attributable', 'attribute_users', 'attribute_id', 'attributable_id')
            ->using(AttributeAssignment::class)
            ->withPivot('value')
            ->withTimestamps()
            ->wherePivotNull('deleted_at');
    }

    protected function casts(): array
    {
        return [
            'type' => AttributeType::class,
            'options' => 'array',
        ];
    }

    /**
     * Find and get the attribute by the $key
     * @param  int|null  $key
     * @return Attribute
     */
    public static function getAttributeById(?int $key = null): self
    {
        return static::with('category')->findOrFail($key);
    }
    public function isSelectable(): bool
    {
        return $this->type === AttributeType::SELECT;
    }

    public function validateValue(mixed $value): bool
    {
        return match ($this->type) {
            AttributeType::STRING => is_string($value),
            AttributeType::NUMBER => is_numeric($value),
            AttributeType::BOOLEAN => is_bool($value),
            AttributeType::DATE => strtotime($value) !== false,
            AttributeType::SELECT => in_array($value, $this->options ?? []),
        };
    }
}
