<?php

namespace App\Models;

use App\Domain\Skill\Contracts\SkillAssignable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SkillSchema extends  Pivot
{
    protected $table = 'skill_schemas';

    protected $fillable = [
        'assignable_type',
        'assignable_id',
        'skill_id',
        'default_level',
    ];

    /**
     * Retrive all the skill
     **/
    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    /**
     * Retrive the user who will be assigned the schema
     **/
    public function assignable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope: Filter by assignable entity
     */
    public function scopeForAssignable($query, SkillAssignable $assignable)
    {
        return $query->where('assignable_type', get_class($assignable))
            ->where('assignable_id', $assignable->id);
    }

    /**
     * Scope: Filter by skill IDs
     */
    public function scopeForSkills($query, array $skillIds)
    {
        return $query->whereIn('skill_id', $skillIds);
    }

    /**
     * Static helper: Remove single skill from assignable's schema
     */
    public static function removeSingle(SkillAssignable $assignable, int $skillId): bool
    {
        return static::where('assignable_type', get_class($assignable))
                ->where('assignable_id', $assignable->id)
                ->where('skill_id', $skillId)
                ->delete() > 0;
    }


    /**
     * Static helper: Bulk delete skills from an assignable's schema
     */
    public static function removeBulk(SkillAssignable $assignable, array $skillIds): int
    {
        return static::where('assignable_type', get_class($assignable))
            ->where('assignable_id', $assignable->id)
            ->whereIn('skill_id', $skillIds)
            ->delete();
    }
}