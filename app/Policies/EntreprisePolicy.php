<?php

namespace App\Policies;

use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EntreprisePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Entreprise');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Entreprise $entreprise): bool
    {
         return $user->hasPermissionTo('Voir Entreprise');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
         return $user->hasPermissionTo('Ajouter Entreprise');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Entreprise $entreprise): bool
    {
         return $user->hasPermissionTo('Edite Entreprise');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Entreprise $entreprise): bool
    {
         return $user->hasPermissionTo('Supprimer Entreprise');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Entreprise $entreprise): bool
    {
        return $user->hasPermissionTo('Supprimer Entreprise');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Entreprise $entreprise): bool
    {
        return $user->hasPermissionTo('Supprimer Entreprise');
    }
}
