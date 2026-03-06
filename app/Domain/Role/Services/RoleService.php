<?php

namespace App\Domain\Role\Services;

use Illuminate\Cache\Repository as Cache;
use Spatie\Permission\Models\Role;

class RoleService
{
    private const string CACHE_KEY = 'roles';
    private const int CACHE_TTL =  3600;

    public function __construct(
        private readonly Cache $cache
    ) {}

    /**
     * Get all the role available
     * @return array
     **/
    public function getAllRoleNames(): array
    {
        $key = self::CACHE_KEY . ':all';
        return $this->cache->tags([self::CACHE_KEY])->remember(
            $key,
            self::CACHE_TTL,
            function (){
                return Role::all()
                ->pluck('name')
                ->map(fn($role) => ucfirst($role))
                ->toArray();
        });
    }
}