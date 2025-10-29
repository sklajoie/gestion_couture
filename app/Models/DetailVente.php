<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailVente extends Model
{
    protected $fillable = [
        'vente_id',
        'stock_entreprise_id',
        'agence_id',
        'quantite',
        'prix_unitaire',
        'montant',
    ];
    public function vente()
    {
        return $this->belongsTo(Vente::class, 'vente_id');
    }

    public function stockEntreprise()
    {
        return $this->belongsTo(StockEntreprise::class, 'stock_entreprise_id');
    }


    protected static function booted()
{
    static::creating(function ($detail) {
        // On récupère automatiquement l'agence du devis parent
        if (empty($detail->agence_id) && $detail->vente) {
            $detail->agence_id = $detail->vente->agence_id;
        }
    });
}

}
