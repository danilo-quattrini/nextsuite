<?php

namespace App\Models;



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

}
