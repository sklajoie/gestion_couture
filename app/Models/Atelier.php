<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atelier extends Model
{
      protected $fillable = [
        'numero',
        'nom',
        'adresse',
        'telephone',
        'contact',
        'email',
        'logo',
        'pied_page',
        'status',
        'user_id',
        'entreprise_id',
        'ville',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
    
    public function etapeMesures()
    {
        return $this->HasMany(EtapeMesure::class);
    }


    
 protected static function booted()
{
    static::creating(function ($model) {
        $now = \Carbon\Carbon::now();
        $prefix = $now->format('ym');

            $countItem = Atelier::count() + 1;
            $suffix = str_pad($countItem , 3, '0', STR_PAD_LEFT); // ex: 001

            $numero = "ATELIER-{$suffix}"; // ex: 2510001
             $model->numero =  $numero;
    });
}
}
