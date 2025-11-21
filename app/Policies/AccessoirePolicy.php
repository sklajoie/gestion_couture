<?php

namespace App\Policies;

use App\Models\Accessoire;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AccessoirePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
       return $user->hasPermissionTo('Voir Accessoires');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Accessoire $accessoire): bool
    {
         return $user->hasPermissionTo('Voir Accessoires');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
         return $user->hasPermissionTo('Ajouter Accessoires');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Accessoire $accessoire): bool
    {
          return $user->hasPermissionTo('Edite Accessoires');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Accessoire $accessoire): bool
    {
         return $user->hasPermissionTo('Supprimer Accessoires');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Accessoire $accessoire): bool
    {
         return $user->hasPermissionTo('Supprimer Accessoires');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Accessoire $accessoire): bool
    {
       return $user->hasPermissionTo('Supprimer Accessoires');
    }
}
