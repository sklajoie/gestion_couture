<?php

namespace App\Policies;

use App\Models\ApprovisionnementStock;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ApprovisionnementStockPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Appro Stock');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ApprovisionnementStock $approvisionnementStock): bool
    {
          return $user->hasPermissionTo('Voir Appro Stock');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
         return $user->hasPermissionTo('Ajouter Appro Stock');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ApprovisionnementStock $approvisionnementStock): bool
    {
         return $user->hasPermissionTo('Edite Appro Stock');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ApprovisionnementStock $approvisionnementStock): bool
    {
         return $user->hasPermissionTo('Supprimer Appro Stock');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ApprovisionnementStock $approvisionnementStock): bool
    {
       return $user->hasPermissionTo('Supprimer Appro Stock');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ApprovisionnementStock $approvisionnementStock): bool
    {
        return $user->hasPermissionTo('Supprimer Appro Stock');
    }
}
