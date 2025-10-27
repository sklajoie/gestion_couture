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
    ];
    public function devis()
    {
        return $this->belongsTo(Devis::class, 'devis_id');
    }

    public function stockEntreprise()
    {
        return $this->belongsTo(StockEntreprise::class, 'stock_entreprise_id');
    }
}
