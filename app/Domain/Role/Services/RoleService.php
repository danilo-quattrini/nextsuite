<?php

namespace App\Domain\Role\Services;

use Illuminate\Cache\Repository as Cache;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\Role;
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
     * Get all permission names assigned to a user (via roles or directly).
     * @param  Authenticatable  $user
     * @return array
     */
    public function getUserPermissions(Authenticatable $user): array
    {
        $this->assertHasRoles($user);

        return $this->cache->tags([self::CACHE_KEY])->remember(
            self::CACHE_KEY . ":user:{$user->getAuthIdentifier()}:permissions",
            self::CACHE_TTL,
            fn() => $user->getAllPermissions()
                ->pluck('name')
                ->toArray()
        );
    }

    /**
     * Check if a user has a specific role.
     * @param  Authenticatable  $user
     * @param  string  $role
     * @return bool
     */
    public function userHasRole(Authenticatable $user, string $role): bool
    {
        $this->assertHasRoles($user);

        return in_array(strtolower($role), array_map('strtolower', $this->getUserRoleNames($user)));
    }

    /**
     * Check if a user has a specific permission.
     * @param  Authenticatable  $user
     * @param  string  $permission
     * @return bool
     */
    public function userHasPermission(Authenticatable $user, string $permission): bool
    {
        $this->assertHasRoles($user);

        return in_array($permission, $this->getUserPermissions($user));
    }

    /**
     * Invalidate all cached role data for a specific user.
     * Call this after role/permission changes.
     * @param  Authenticatable  $user
     */
    public function invalidateUserCache(Authenticatable $user): void
    {
        $this->cache->tags([self::CACHE_KEY])->forget(
            self::CACHE_KEY . ":user:{$user->getAuthIdentifier()}:roles"
        );
        $this->cache->tags([self::CACHE_KEY])->forget(
            self::CACHE_KEY . ":user:{$user->getAuthIdentifier()}:permissions"
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