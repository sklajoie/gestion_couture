<?php

namespace App\Policies;

use App\Models\Caisse;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CaissePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Caisse');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Caisse $caisse): bool
    {
        return $user->hasPermissionTo('Voir Caisse');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Ajouter Caisse');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Caisse $caisse): bool
    {
        return $user->hasPermissionTo('Edite Caisse');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Caisse $caisse): bool
    {
      return $user->hasPermissionTo('Supprimer Caisse');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Caisse $caisse): bool
    {
       return $user->hasPermissionTo('Supprimer Caisse');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Caisse $caisse): bool
    {
       return $user->hasPermissionTo('Supprimer Caisse');
    }
}
