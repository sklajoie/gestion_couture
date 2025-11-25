<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MesureChemise extends Model
{
      protected $fillable = [
        'Reference',
        'Tour_cou',
        'Tour_poitrine',
        'Tour_taille',
        'Tour_bassin',
        'Largeur_epaule',
        'Longueur_manche',
        'Tour_bas',
        'Tour_poignet',
        'Longueur_chemise',
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
        'entreprise_id',
        'designation',
    ];
    protected $casts = [
    'Model_mesure' => 'array',
      ];




    public function dernierEtape()
    {
        return $this->belongsTo(EtapeProduction::class, 'etape_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produitCouture()
    {
        return $this->hasMany(ProduitCouture::class);
    }

    public function imageModel()
    {
        return $this->belongsTo(imagesModel::class);
    }

    public function etapeMesures()
    {
        return $this->hasMany(EtapeMesure::class);
    }

    

//   public function getDernierEtapeAttribute()
// {
//     return $this->etapeMesures
//         ->where('is_completed', true)
//         ->sortByDesc('date_fin')
//         ->first()?->etapeProduction?->nom ?? '—';
// }


        protected static function booted()
    {
        // static::saved(function ($model) {
        //     dd($model->etapeMesures);
        // });
        static::deleting(function ($etapeMesures) {
            $etapeMesures->etapeMesures()->delete();
            $etapeMesures->produitCouture()->delete();
        });
    }


}
