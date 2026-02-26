<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'rating',
        'comment',
        'author_id',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get the reviewable model class
     */
    public function getModelClass(): Model|string
    {
        return $this->reviewable_type;
    }

    /**
     * Get the reviewable model name
     */
    public function getModelName(): string
    {
        return class_basename($this->reviewable_type);
    }

    /**
     * Get the reviewable model class
     */
    public function getModelId(): int
    {
        return $this->reviewable_id;
    }
}
