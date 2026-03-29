<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;

class JoinRequest extends Model
{
    protected $fillable = ['user_id', 'company_id', 'status'];

    // ==== RELATIONS ====
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }


    // ==== HELPER METHODS ====
    public static function getCompanyRequests(
        ?int $companyId = null
    ): LengthAwarePaginator
    {
        return JoinRequest::with('user')
            ->where('company_id', $companyId)
            ->where('status', 'pending')
            ->latest()
            ->paginate(6);
    }
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
