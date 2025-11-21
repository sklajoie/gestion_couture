<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vente;
use Illuminate\Auth\Access\Response;

class VentePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
       return $user->hasPermissionTo('Voir Vente');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vente $vente): bool
    {
         return $user->hasPermissionTo('Voir Vente');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
         return $user->hasPermissionTo('Ajouter Vente');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vente $vente): bool
    {
         return $user->hasPermissionTo('Edite Vente');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vente $vente): bool
    {
        return $user->hasPermissionTo('Supprimer Vente');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Vente $vente): bool
    {
        return $user->hasPermissionTo('Supprimer Vente');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Vente $vente): bool
    {
        return $user->hasPermissionTo('Supprimer Vente');
    }
}
