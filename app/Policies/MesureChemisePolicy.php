<?php

namespace App\Policies;

use App\Models\MesureChemise;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MesureChemisePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Mesure Chemise');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MesureChemise $mesureChemise): bool
    {
        return $user->hasPermissionTo('Voir Mesure Chemise');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Ajouter Mesure Chemise');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MesureChemise $mesureChemise): bool
    {
        return $user->hasPermissionTo('Edite Mesure Chemise');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MesureChemise $mesureChemise): bool
    {
        return $user->hasPermissionTo('Supprimer Mesure Chemise');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MesureChemise $mesureChemise): bool
    {
       return $user->hasPermissionTo('Supprimer Mesure Chemise');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MesureChemise $mesureChemise): bool
    {
       return $user->hasPermissionTo('Supprimer Mesure Chemise');
    }
}
