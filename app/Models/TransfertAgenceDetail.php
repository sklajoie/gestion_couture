<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransfertAgenceDetail extends Model
{
    protected $fillable = [
        'transfert_agence_id',
        'stock_entreprise_id',
        'quantite',
    ];

    public function transfertAgence()
    {
        return $this->belongsTo(TransfertAgence::class);
    }
    public function stockEntreprise()
    {
        return $this->belongsTo(StockEntreprise::class);
    }

}
