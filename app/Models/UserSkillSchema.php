<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserSkillSchema extends  Pivot
{
    protected $table = 'user_skill_schema';

    protected $fillable = [
        'user_id',
        'skill_id',
        'default_level'
    ];

    /**
     * Retrive all the skill
     **/
    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    /**
     * Retrive the user who owns the schema
     **/
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}