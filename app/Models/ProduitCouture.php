<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduitCouture extends Model
{
      protected $fillable = [
        'produit_id',
        'quantite',
        'prix_unitaire',
        'total',
        'detail',
        'mesure_chemise_id',
        'mesure_robe_id',
        'mesure_pantalon_id',
        'mesure_ensemble_id',
    ];

     public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
     public function produitChemise()
    {
        return $this->belongsTo(MesureChemise::class);
    }
     public function produitPantalon()
    {
        return $this->belongsTo(MesurePantalon::class);
    }
     public function produitRobe()
    {
        return $this->belongsTo(MesureRobe::class);
    }
     public function produitEnsemnble()
    {
        return $this->belongsTo(MesureEnsemble::class);
    }
}
