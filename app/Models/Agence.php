<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agence extends Model
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
    public function devis()
    {
        return $this->hasMany(Devis::class);
    }
    public function vente()
    {
        return $this->hasMany(Vente::class);
    }
    public function stockAgence()
    {
        return $this->hasMany(StockAgence::class);
    }
    public function distribution()
    {
        return $this->hasMany(DistributionAgence::class);
    }
    public function versement()
    {
        return $this->hasMany(Versement::class);
    }




    protected static function booted()
{
    static::creating(function ($model) {
        $now = \Carbon\Carbon::now();
        $prefix = $now->format('ym');

            $countItem = Agence::count() + 1;
            $suffix = str_pad($countItem , 3, '0', STR_PAD_LEFT); // ex: 001

            $numero = "AGENCE-{$suffix}"; // ex: 2510001
             $model->numero =  $numero;
    });
}



}
