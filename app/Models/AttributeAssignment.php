<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeAssignment extends MorphPivot
{
    use SoftDeletes;

    protected $table = 'attribute_users';

    protected $casts = [
        'value' => 'array',
    ];
}
