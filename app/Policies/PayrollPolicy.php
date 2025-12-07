<?php

namespace App\Policies;

use App\Models\Payroll;
use App\Models\User;

class PayrollPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Finance');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Payroll $payroll): bool
    {
        return $user->hasRole('Finance');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Finance');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Payroll $payroll): bool
    {
        return $user->hasRole('Finance');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Payroll $payroll): bool
    {
        return $user->hasRole('Finance');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Payroll $payroll): bool
    {
        return $user->hasRole('Finance');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Payroll $payroll): bool
    {
        return $user->hasRole('Finance');
    }
}
