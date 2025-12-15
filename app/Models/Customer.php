<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'profile_photo_url',
        'full_name',
        'email',
        'phone',
        'dob',
        'gender',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'datetime',
        ];
    }
}
