<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieProduit extends Model
{
    protected $fillable = [
        'type',
        'nom',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    public function produit()
    {
        return $this->HasMany(Produit::class);
    }

    
}
