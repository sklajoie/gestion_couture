<?php

namespace App\Policies;

use App\Models\CategorieProduit;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategorieProduitPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Voir Categorie Produit');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CategorieProduit $categorieProduit): bool
    {
        return $user->hasPermissionTo('Voir Categorie Produit');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Ajouter Categorie Produit');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CategorieProduit $categorieProduit): bool
    {
        return $user->hasPermissionTo('Edite Categorie Produit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CategorieProduit $categorieProduit): bool
    {
        return $user->hasPermissionTo('Supprimer Categorie Produit');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CategorieProduit $categorieProduit): bool
    {
        return $user->hasPermissionTo('Supprimer Categorie Produit');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CategorieProduit $categorieProduit): bool
    {
        return $user->hasPermissionTo('Supprimer Categorie Produit');
    }
}
