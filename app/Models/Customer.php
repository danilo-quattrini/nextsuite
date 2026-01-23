<?php

namespace App\Models;

use App\Domain\Skill\Contracts\SkillAssignable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class   Customer extends Model implements SkillAssignable
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'profile_photo_url',
        'full_name',
        'email',
        'phone',
        'dob',
        'gender',
        'nationality',
        'company_id',
        'user_id',
    ];

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'skill_customer', 'customer_id', 'skill_id')
            ->withPivot(['years', 'level']);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'customer_attribute', 'customer_id', 'attribute_id')
            ->using(CustomerAttribute::class)
            ->withPivot('value')
            ->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'dob' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all the customer's reviews.
     */
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Get all the customer's documents.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Add a skill to a customer, syncWithoutDetaching = remove already existing record and add new ones.
     */
    public function addSkill(int $id, int $level, int $years): void
    {
        $this->skills()->attach([
            $id => [
                'level' => $level,
                'years' => $years
            ]
        ]);
    }
}
