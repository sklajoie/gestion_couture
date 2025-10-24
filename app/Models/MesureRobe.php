<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MesureRobe extends Model
{
       protected $fillable = [
        'Reference',
        'Epaule',
        'Tour_poitrine',
        'Tour_taille',
        'Tour_bassin',
        'Longueur_bassin',
        'Carrure_dos',
        'Longueur_buste',
        'Tour_bras',
        'Tour_manche',
        'Longueur_robe',
        'Longueur_manche',
        'Type',
        'Description',
        'user_id',
        'Model_mesure',
         'status',
        'is_archived',
        'total_produit',
        'main_oeuvre',
        'couleur_id',
        'taille_id',
        'prix_couture',
        'prix_vente',
        'etape_id',
        
    ];

        protected $casts = [
            'Model_mesure' => 'array',
      ];
      

       public function dernierEtape()
    {
        return $this->belongsTo(EtapeProduction::class, 'etape_id');
    }
          public function etapeMesures()
    {
        return $this->hasMany(EtapeMesure::class);
    }
            public function produitCouture()
    {
        return $this->hasMany(ProduitCouture::class);
    }

            protected static function booted()
    {
        static::deleting(function ($etapeMesures) {
            $etapeMesures->etapeMesures()->delete();
            $etapeMesures->produitCouture()->delete();
        });
    }
}
