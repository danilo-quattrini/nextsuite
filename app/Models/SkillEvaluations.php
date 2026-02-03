<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkillEvaluations extends Model
{
    protected $fillable = [
        'skill_customer_id',
        'evaluator_id',
        'level',
        'notes',
        'evaluated_at'
    ];

    protected $casts = [
        'evaluated_at' => 'datetime'
    ];

    /**
     * Retrive all the evaluators from the SkillEvaluation model
     */
    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    /**
     * Retrive all the skills from the SkillCustomer  model
     */

    public function skillCustomer(): BelongsTo
    {
        return $this->belongsTo(SkillCustomers::class, 'skill_customer_id');
    }
}
