<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;


class CustomerPolicy
{

    /**
     * Resolve the company the user is acting within.
     * Owner: via company()->owner_id
     * Employee: via company_employee pivot
     *
     * @param  User  $user who owns or it's part of the company
     * @param  int  $companyId company id that owns or take part the user.
     *
     * @return bool true if it's part of the company, or it's an owner of it
     * otherwise it will return false that means it's not part of the company.
     */
    private function resolveCompany(User $user, int $companyId): bool
    {

        if ($user->company?->id === $companyId) {
            return true;
        }

        return $user->companies()
            ->wherePivot('company_id', $companyId)
            ->exists();
    }

    /**
     * View any customer inside the system.
     * @param  User  $user the user who it's authenticated
     * @return bool true if the permission is available.
     * */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('customer.read');
    }

    /**
     * Any role with customer.read can view,
     * but only within their own company.
     */
    public function view(User $user, Customer $customer): bool
    {
        return $user->hasPermissionTo('customer.read')
            && $this->resolveCompany($user, $customer->company_id);
    }

    /**
     * Any role with customer.create can create a customer.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('customer.create');
    }

    /**
     * Any role with customer.update can edit a customer,
     * but only within their own company or if it's the owner.
     */
    public function update(User $user, Customer $customer): bool
    {
        return $user->hasPermissionTo('customer.update')
            && $this->resolveCompany($user, $customer->company_id);
    }

    /**
     * Any role with customer.delete can delete a customer,
     * but only within their own company or if it's the owner.
     */
    public function delete(User $user, Customer $customer): bool
    {
        return $user->hasPermissionTo('customer.delete')
            && $this->resolveCompany($user, $customer->company_id);
    }
}
