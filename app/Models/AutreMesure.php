<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutreMesure extends Model
{
    
     protected $fillable = [
       'Reference',
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
    public function autreMesureDetail()
    {
        return $this->hasMany(AutreMesureDetail::class);
    }


        protected static function booted()
    {
         static::creating(function ($model) {
            $now = \Carbon\Carbon::now();
            $prefix = $now->format('ym');

            do {
                $suffix = str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
                $numero = "AM{$prefix}{$suffix}";
            } while (self::where('Reference', $numero)->exists());

            $model->Reference = $numero;
        });

        static::deleting(function ($etapeMesures) {
            $etapeMesures->etapeMesures()->delete();
            $etapeMesures->produitCouture()->delete();
            $etapeMesures->autreMesureDetail()->delete();
        });
    }

  
}

