<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SkillCustomers extends Pivot
{
    protected $table = 'skill_customers';

    public $incrementing = true;

    protected $fillable = [
        'level',
        'years',
        'notes',
        'skill_id',
        'customer_id',
        'user_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Retrive all the skill
     **/
    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    /**
     * Retrive all the customers
    **/
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Retrive all the evaluations from the SkillEvaluation model
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(SkillEvaluations::class, 'skill_customer_id');
    }

    public static function findOrCreateSkill(Skill $skill, Customer $customer)
    {

        return static::firstOrCreate(
            [
                'customer_id' => $customer->id,
                'skill_id' => $skill->id
            ],
            [
                'level' => 0,
                'years' => null,
            ]
        );
    }

    /**
     * Add an evaluated skill to a customer
     */
    public function addEvaluation(User $evaluator, int $level, ?string $notes = null)
    {
        $evaluation = $this->evaluations()->create([
            'evaluator_id' => $evaluator->id,
            'level' => $level,
            'notes' => $notes,
            'evaluated_at' => now(),
        ]);

        $this->recalculateLevel();

        return $evaluation;
    }

    /**
     * Remove a skill from a customer
     */
    public function removeSkillFromCustomer(
        SkillAssignable $user,
        int $skillId
    ): void
    {
        self::where('customer_id', $user->id)
            ->where('skill_id', $skillId)
            ->delete();
    }
    /**
     * Remove a set of skills from a customer
     */
    public function removeManySkillFromCustomer(
        SkillAssignable $user,
        array $skillIds
    ): void
    {
        foreach ($skillIds as $skillId) {
            $this->removeSkillFromCustomer($user, $skillId);
        }
    }
    /**
     * Calculate the average from all the soft skill
     **/
    public function recalculateLevel(): void
    {
        $avgLevel = $this->evaluations()->avg('level');

        if ($avgLevel !== null) {
            $this->update(['level' => round($avgLevel, 1)]);
        }
    }
}
