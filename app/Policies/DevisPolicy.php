<?php

namespace App\Policies;

use App\Models\Devis;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DevisPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Devis');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Devis $devis): bool
    {
       return $user->hasPermissionTo('Voir Devis');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Ajouter Devis');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Devis $devis): bool
    {
        return $user->hasPermissionTo('Edite Devis');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Devis $devis): bool
    {
        return $user->hasPermissionTo('Supprimer Devis');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Devis $devis): bool
    {
        return $user->hasPermissionTo('Supprimer Devis');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Devis $devis): bool
    {
        return $user->hasPermissionTo('Supprimer Devis');
    }
}
