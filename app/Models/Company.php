<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;

    private const string CACHE_KEY = 'companies';
    private const int CACHE_TTL = 3600;


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

    protected static function booted(): void
    {
        static::created(fn() => self::clearAllContexts());
        static::updated(fn() => self::clearAllContexts());
        static::deleted(fn() => self::clearAllContexts());
    }

    // ==================== CACHE OPERATION ====================

    protected static function clearAllContexts(): void
    {
        Cache::tags([self::CACHE_KEY])->flush();
    }

    // ====== RELATIONSHIP =====
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

    public function employee(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_employee', 'company_id', 'employee_id');
    }
}
