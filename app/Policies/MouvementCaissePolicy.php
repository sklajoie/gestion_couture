<?php

namespace App\Policies;

use App\Models\MouvementCaisse;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MouvementCaissePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Mouvement Caisse');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MouvementCaisse $mouvementCaisse): bool
    {
        return $user->hasPermissionTo('Voir Mouvement Caisse');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Ajouter Mouvement Caisse');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MouvementCaisse $mouvementCaisse): bool
    {
        return $user->hasPermissionTo('Edite Mouvement Caisse');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MouvementCaisse $mouvementCaisse): bool
    {
       return $user->hasPermissionTo('Supprimer Mouvement Caisse');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MouvementCaisse $mouvementCaisse): bool
    {
       return $user->hasPermissionTo('Supprimer Mouvement Caisse');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MouvementCaisse $mouvementCaisse): bool
    {
       return $user->hasPermissionTo('Supprimer Mouvement Caisse');
    }
}
