<?php

namespace App\Policies;

use App\Models\AutreMesure;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AutreMesurePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Autre Mesure');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AutreMesure $autreMesure): bool
    {
        return $user->hasPermissionTo('Voir Autre Mesure');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Ajouter Autre Mesure');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AutreMesure $autreMesure): bool
    {
        return $user->hasPermissionTo('Edite Autre Mesure');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AutreMesure $autreMesure): bool
    {
        return $user->hasPermissionTo('Supprimer Autre Mesure');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AutreMesure $autreMesure): bool
    {
        return $user->hasPermissionTo('Supprimer Autre Mesure');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AutreMesure $autreMesure): bool
    {
        return $user->hasPermissionTo('Supprimer Autre Mesure');
    }
}
