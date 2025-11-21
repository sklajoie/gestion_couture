<?php

namespace App\Policies;

use App\Models\MesurePantalon;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MesurePantalonPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Mesure Pantalon');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MesurePantalon $mesurePantalon): bool
    {
        return $user->hasPermissionTo('Voir Mesure Pantalon');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Ajouter Mesure Pantalon');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MesurePantalon $mesurePantalon): bool
    {
        return $user->hasPermissionTo('Edite Mesure Pantalon');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MesurePantalon $mesurePantalon): bool
    {
        return $user->hasPermissionTo('Supprimer Mesure Pantalon');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MesurePantalon $mesurePantalon): bool
    {
         return $user->hasPermissionTo('Supprimer Mesure Pantalon');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MesurePantalon $mesurePantalon): bool
    {
         return $user->hasPermissionTo('Supprimer Mesure Pantalon');
    }
}
