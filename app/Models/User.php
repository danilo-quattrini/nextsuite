<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    private const string CACHE_KEY = "user";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'profile_photo_path'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Clear the cache if the user has been created, updated, or deleted.
     */
    protected static function booted(): void
    {
        static::created(fn() => self::clearAllContexts());
        static::updated(fn() => self::clearAllContexts());
        static::deleted(fn() => self::clearAllContexts());
    }

    protected static function clearAllContexts(): void
    {
        Cache::tags([self::CACHE_KEY])->flush();
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'owner_id');
    }

    public function customer(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Get the attribute polymorph model.
     */
    public function attributes(): MorphToMany
    {
        return $this->morphToMany(Attribute::class, 'attributable', 'attribute_users', 'attributable_id', 'attribute_id')
            ->using(AttributeAssignment::class)
            ->withPivot('value')
            ->withTimestamps()
            ->wherePivotNull('deleted_at');
    }

    /**
     * Get the skill schema create by the user.
     */
    public function skillSchema(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'user_skill_schema')
            ->using(SkillSchema::class)
            ->withPivot(['default_level'])
            ->withTimestamps();
    }

    /**
     * Get the user company if exists.
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function getCompanyWithUsers(): ?Company
    {
        if (!$this->relationLoaded('company')) {
            $this->load('company.users');
        }
        return $this->company;
    }

    /**
     * Check if the user has a company.
     */
    public function hasCompany(): bool
    {
        return $this->company()->exists();
    }

    /**
     * Get the cache key.
     */
    public function getCacheKey(?int $userId = null): string
    {
        $id = $userId ?? Auth::id();

        if (!$id) {
            throw new \RuntimeException('Cannot generate cache key: No authenticated user');
        }

        return self::CACHE_KEY . ':' . $id;
    }
}
