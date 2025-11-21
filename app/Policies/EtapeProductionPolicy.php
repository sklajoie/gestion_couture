<?php

namespace App\Policies;

use App\Models\EtapeProduction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EtapeProductionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Etape Production');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EtapeProduction $etapeProduction): bool
    {
         return $user->hasPermissionTo('Voir Etape Production');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
          return $user->hasPermissionTo('Ajouter Etape Production');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EtapeProduction $etapeProduction): bool
    {
          return $user->hasPermissionTo('Edite Etape Production');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EtapeProduction $etapeProduction): bool
    {
          return $user->hasPermissionTo('Supprimer Etape Production');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EtapeProduction $etapeProduction): bool
    {
         return $user->hasPermissionTo('Supprimer Etape Production');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EtapeProduction $etapeProduction): bool
    {
         return $user->hasPermissionTo('Supprimer Etape Production');
    }
}
