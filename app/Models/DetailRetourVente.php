<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailRetourVente extends Model
{
protected $fillable = ['retour_vente_id','prix_unitaire','montant','detail_vente_id','quantite','stock_entreprise_id','motif'];

    public function retour()
    {
        return $this->belongsTo(RetourVente::class);
    }

    public function detailVente()
    {
        return $this->belongsTo(DetailVente::class);
    }
    public function stockEntreprise()
    {
        return $this->belongsTo(StockEntreprise::class);
    }
}
