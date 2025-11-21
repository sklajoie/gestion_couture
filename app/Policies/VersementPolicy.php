<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Versement;
use Illuminate\Auth\Access\Response;

class VersementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Versement');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Versement $versement): bool
    {
        return $user->hasPermissionTo('Voir Versement');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Ajouter Versement');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Versement $versement): bool
    {
        return $user->hasPermissionTo('Edite Versement');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Versement $versement): bool
    {
        return $user->hasPermissionTo('Supprimer Versement');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Versement $versement): bool
    {
        return $user->hasPermissionTo('Supprimer Versement');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Versement $versement): bool
    {
        return $user->hasPermissionTo('Supprimer Versement');
    }
}
