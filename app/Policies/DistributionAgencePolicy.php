<?php

namespace App\Policies;

use App\Models\DistributionAgence;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DistributionAgencePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Distribution');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DistributionAgence $distributionAgence): bool
    {
        return $user->hasPermissionTo('Voir Distribution');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Ajouter Distribution');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DistributionAgence $distributionAgence): bool
    {
       return $user->hasPermissionTo('Edite Distribution');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DistributionAgence $distributionAgence): bool
    {
        return $user->hasPermissionTo('Supprimer Distribution');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DistributionAgence $distributionAgence): bool
    {
        return $user->hasPermissionTo('Supprimer Distribution');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DistributionAgence $distributionAgence): bool
    {
        return $user->hasPermissionTo('Supprimer Distribution');
    }
}
