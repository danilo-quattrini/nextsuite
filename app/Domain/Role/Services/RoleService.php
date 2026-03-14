<?php

namespace App\Domain\Role\Services;

use Illuminate\Cache\Repository as Cache;
use Illuminate\Contracts\Auth\Authenticatable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class RoleService
{
    private const string CACHE_KEY = 'roles';
    private const int CACHE_TTL =  3600;

    public function __construct(
        private readonly ?Cache $cache
    ) {}

    /**
     * Get all the roles from the user
     * @param  Authenticatable  $user
     * @return array
     */
    public function getUserRoleNames(Authenticatable $user): array
    {
        $this->assertHasRoles($user);
        return $this->cache->tags([self::CACHE_KEY])->remember(
            self::CACHE_KEY . ":user:{$user->getAuthIdentifier()}:roles",
            self::CACHE_TTL,
            fn() => $user->roles
                ->pluck('name')
                ->map(fn($role) => ucfirst($role))
                ->toArray()
        );
    }
    /**
     * Get all the role available in the system.
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

    /**
     * Ensure the model uses Spatie's HasRoles trait before calling role methods.
     */
    private function assertHasRoles(Authenticatable $user): void
    {
        if (!in_array(HasRoles::class, class_uses_recursive($user))) {
            throw new \LogicException(
                class_basename($user) . ' must use the HasRoles trait to use RoleService.'
            );
        }
    }
}