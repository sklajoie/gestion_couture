<?php

namespace App\Policies;

use App\Models\Marque;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MarquePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Marque');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Marque $marque): bool
    {
        return $user->hasPermissionTo('Voir Marque');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Ajouter Marque');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Marque $marque): bool
    {
        return $user->hasPermissionTo('Edite Marque');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Marque $marque): bool
    {
        return $user->hasPermissionTo('Supprimer Marque');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Marque $marque): bool
    {
        return $user->hasPermissionTo('Supprimer Marque');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Marque $marque): bool
    {
        return $user->hasPermissionTo('Supprimer Marque');
    }
}
