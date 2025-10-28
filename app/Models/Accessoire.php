<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accessoire extends Model
{
     protected $fillable = [
        'nom',
        'code_barre',
        'description',
        'prix_achat',
        'prix_vente',
        'stock_minimum',
        'stock',
        'unite',
        'stockable',
        'user_id',
        'categorie_produit_id',
        'couleur_id',
        'taille_id',
        'marque_id',
    ];

    public function categorieProduit()
    {
        return $this->belongsTo(CategorieProduit::class);
    }
    public function marque()
    {
        return $this->belongsTo(Marque::class);
    }
    public function taille()
    {
        return $this->belongsTo(Taille::class);
    }
    public function couleur()
    {
        return $this->belongsTo(Couleur::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }  

        protected static function booted()
    {
        static::creating(function ($model) {
            $now = \Carbon\Carbon::now();
            $prefix = $now->format('ym');

            do {
                $suffix = str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
                $numero = "A{$prefix}{$suffix}";
            } while (self::where('code_barre', $numero)->exists());

            $model->code_barre = $numero;
        });
    }


}

