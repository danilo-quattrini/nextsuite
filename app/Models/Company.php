<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;
    protected $fillable = [
        'company_photo',
        'name',
        'website',
        'email',
        'vat_number',
        'address_line',
        'city',
        'phone',
        'owner_id',
    ];

    public function fields(): BelongsToMany
    {
        return $this->belongsToMany(Field::class, 'company_field', 'company_id', 'field_id');
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
