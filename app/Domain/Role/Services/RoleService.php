<?php

namespace App\Domain\Role\Services;

use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class RoleService
{
    private const string CACHE_KEY = 'roles';
    private const int CACHE_TTL =  3600;

    /**
     * Get all the role available
     * @return array
     **/
    public static function getAllRoleNames(): array
    {
        $key = self::CACHE_KEY . ':all';
        return Cache::tags([self::CACHE_KEY])->remember($key, self::CACHE_TTL, function (){
            return Role::all()
                ->pluck('name')
                ->map(fn($role) => ucfirst($role))
                ->toArray();
        });
    }
}