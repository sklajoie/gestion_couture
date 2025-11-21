<?php

namespace App\Policies;

use App\Models\ClotureCaisse;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClotureCaissePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Cloture Caisse');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClotureCaisse $clotureCaisse): bool
    {
         return $user->hasPermissionTo('Voir Cloture Caisse');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
         return $user->hasPermissionTo('Ajouter Cloture Caisse');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClotureCaisse $clotureCaisse): bool
    {
         return $user->hasPermissionTo('Edite Cloture Caisse');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClotureCaisse $clotureCaisse): bool
    {
         return $user->hasPermissionTo('Supprimer Cloture Caisse');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ClotureCaisse $clotureCaisse): bool
    {
       return $user->hasPermissionTo('Supprimer Cloture Caisse');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ClotureCaisse $clotureCaisse): bool
    {
       return $user->hasPermissionTo('Supprimer Cloture Caisse');
    }
}
