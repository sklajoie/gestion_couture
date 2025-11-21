<?php

namespace App\Policies;

use App\Models\StockAgence;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StockAgencePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Stock Agence');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StockAgence $stockAgence): bool
    {
        return $user->hasPermissionTo('Voir Stock Agence');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StockAgence $stockAgence): bool
    {
         return $user->hasPermissionTo('Edite Stock Agence');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StockAgence $stockAgence): bool
    {
         return $user->hasPermissionTo('Supprimer Stock Agence');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StockAgence $stockAgence): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StockAgence $stockAgence): bool
    {
        return false;
    }
}
