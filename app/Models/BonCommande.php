<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonCommande extends Model
{
    protected $fillable = [
        'reference',
        'fournisseur_id',
        'date_commande',
        'total_brut',
        'remise',
        'total_hors_taxes',
        'tva',
        'total_ttc',
        'avance',
        'solde',
        'remarques',
        'statut',
        'user_id',
        'entreprise_id',
    ];

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function details()
    {
        return $this->hasMany(DetailBonCommande::class);
    }

    public function approvisionnements()
    {
        return $this->hasMany(Approvisionnement::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
        protected static function booted()
    {
        static::deleting(function ($bonCommande) {
            $bonCommande->details()->delete();
        });
    }

}
