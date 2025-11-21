<?php

namespace App\Policies;

use App\Models\Couleur;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CouleurPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Couleur');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Couleur $couleur): bool
    {
       return $user->hasPermissionTo('Voir Couleur');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Ajouter Couleur');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Couleur $couleur): bool
    {
         return $user->hasPermissionTo('Edite Couleur');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Couleur $couleur): bool
    {
         return $user->hasPermissionTo('Supprimer Couleur');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Couleur $couleur): bool
    {
         return $user->hasPermissionTo('Supprimer Couleur');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Couleur $couleur): bool
    {
         return $user->hasPermissionTo('Supprimer Couleur');
    }
}
