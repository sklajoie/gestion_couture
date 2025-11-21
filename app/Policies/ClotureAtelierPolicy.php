<?php

namespace App\Policies;

use App\Models\ClotureAtelier;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClotureAtelierPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Cloture Atelier');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClotureAtelier $clotureAtelier): bool
    {
        return $user->hasPermissionTo('Voir Cloture Atelier');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Ajouter Cloture Atelier');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClotureAtelier $clotureAtelier): bool
    {
        return $user->hasPermissionTo('Edite Cloture Atelier');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClotureAtelier $clotureAtelier): bool
    {
       return $user->hasPermissionTo('Supprimer Cloture Atelier');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ClotureAtelier $clotureAtelier): bool
    {
        return $user->hasPermissionTo('Supprimer Cloture Atelier');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ClotureAtelier $clotureAtelier): bool
    {
        return $user->hasPermissionTo('Supprimer Cloture Atelier');
    }
}
