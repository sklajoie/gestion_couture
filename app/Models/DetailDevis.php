<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailDevis extends Model
{
        protected $fillable = [
        'devis_id',
        'stock_entreprise_id',
        'agence_id',
        'quantite',
        'prix_unitaire',
        'montant',
        'entreprise_id',
    ];
    public function devis()
    {
        return $this->belongsTo(Devis::class, 'devis_id');
    }

    public function stockEntreprise()
    {
        return $this->belongsTo(StockEntreprise::class, 'stock_entreprise_id');
    }


    protected static function booted()
{
    static::creating(function ($detail) {
        // On récupère automatiquement l'agence du devis parent
        if (empty($detail->agence_id) && $detail->devis) {
            $detail->agence_id = $detail->devis->agence_id;
        }
        //dd($detail->agence_id);
    });
}

}
