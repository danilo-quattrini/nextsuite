<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillEvaluations extends Model
{
    protected $fillable = [
        'level',
        'notes',
        'skill_customer_id',
        'evaluator_id'
    ];

}
