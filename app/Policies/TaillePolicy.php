<?php

namespace App\Policies;

use App\Models\Taille;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaillePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Taille');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Taille $taille): bool
    {
        return $user->hasPermissionTo('Voir Taille');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
         return $user->hasPermissionTo('Ajouter Taille');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Taille $taille): bool
    {
         return $user->hasPermissionTo('Edite Taille');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Taille $taille): bool
    {
         return $user->hasPermissionTo('Supprimer Taille');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Taille $taille): bool
    {
       return $user->hasPermissionTo('Supprimer Taille');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Taille $taille): bool
    {
       return $user->hasPermissionTo('Supprimer Taille');
    }
}
