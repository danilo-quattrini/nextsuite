<?php

namespace App\Models;

use App\Enums\AttributeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'customer_attribute', 'attribute_id', 'customer_id')
            ->using(CustomerAttribute::class)
            ->withPivot(['value'])
            ->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'type' => AttributeType::class,
            'options' => 'array',
        ];
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
