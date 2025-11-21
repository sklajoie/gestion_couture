<?php

namespace App\Policies;

use App\Models\MouvementNature;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MouvementnaturePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Nature Mouvement');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MouvementNature $mouvementNature): bool
    {
       return $user->hasPermissionTo('Voir Nature Mouvement');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Ajouter Nature Mouvement');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MouvementNature $mouvementNature): bool
    {
        return $user->hasPermissionTo('Edite Nature Mouvement');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MouvementNature $mouvementNature): bool
    {
       return $user->hasPermissionTo('Supprimer Nature Mouvement');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MouvementNature $mouvementNature): bool
    {
       return $user->hasPermissionTo('Supprimer Nature Mouvement');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MouvementNature $mouvementNature): bool
    {
       return $user->hasPermissionTo('Supprimer Nature Mouvement');
    }
}
