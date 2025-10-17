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
    ];

    public function categorie()
    {
        return $this->belongsTo(CategorieProduit::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }   
}
