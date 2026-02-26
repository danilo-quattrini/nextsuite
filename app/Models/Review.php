<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'rating',
        'comment',
        'author_id',
        'reviewable_type',
        'reviewable_id',
    ];

    protected static function booted(): void
    {
        $clearReviewableCache = function($review) {
            $review->clearReviewableCache();
        };

        static::created($clearReviewableCache);
        static::updated($clearReviewableCache);
        static::deleted($clearReviewableCache);
    }

    // ==================== CACHE OPERATION ====================

    public function clearReviewableCache(): void
    {
        if(!$this->reviewable_id || !$this->reviewable_type) {
            return;
        }

        $model = $this->getModelClass();

        if(method_exists($model, 'clearModelCache' )){
            $model::clearModelCache($this->getModelId());
        }

        Cache::tags([strtolower($this->getModelName())])->flush();
    }

    // ==================== RELATIONSHIPS ====================

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
