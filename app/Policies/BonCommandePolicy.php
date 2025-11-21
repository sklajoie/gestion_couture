<?php

namespace App\Policies;

use App\Models\BonCommande;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BonCommandePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Bon Commande');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BonCommande $bonCommande): bool
    {
         return $user->hasPermissionTo('Voir Bon Commande');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
          return $user->hasPermissionTo('Ajouter Bon Commande');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BonCommande $bonCommande): bool
    {
          return $user->hasPermissionTo('Editer Bon Commande');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BonCommande $bonCommande): bool
    {
        return $user->hasPermissionTo('Supprimer Bon Commande');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BonCommande $bonCommande): bool
    {
        return $user->hasPermissionTo('Supprimer Bon Commande');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BonCommande $bonCommande): bool
    {
        return $user->hasPermissionTo('Supprimer Bon Commande');
    }
}
