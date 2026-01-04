<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CustomerAttribute extends Pivot
{
    protected $table = 'customer_attribute';

    protected $casts = [
        'value' => 'array',
    ];
}