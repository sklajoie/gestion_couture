<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
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
        'image',
        'archived',
        'type',
        'entreprise_id',
    ];

    public function categorieProduit()
    {
        return $this->belongsTo(CategorieProduit::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
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


         protected static function booted()
    {
        static::creating(function ($model) {
            $now = \Carbon\Carbon::now();
            $prefix = $now->format('ym');

            do {
                $suffix = str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
                $numero = "P{$prefix}{$suffix}";
            } while (self::where('code_barre', $numero)->exists());

            $model->code_barre = $numero;
        });

    }
}
