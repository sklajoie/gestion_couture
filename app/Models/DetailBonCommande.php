<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBonCommande extends Model
{
    protected $fillable = [
        'bon_commande_id',
        'produit_id',
        'quantite',
        'prix_unitaire',
        'total',
        'is_approvisionne',
        'quantite_approvisionnee',
        'entreprise_id',
    ];

    public function bonCommande()
    {
        return $this->belongsTo(BonCommande::class);
    }
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
