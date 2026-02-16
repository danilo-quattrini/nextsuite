<?php

namespace App\Models;

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
}