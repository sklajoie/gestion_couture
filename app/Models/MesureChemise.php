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
    ];
    protected $casts = [
    'Model_mesure' => 'array',
      ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function imageModel()
    {
        return $this->belongsTo(imagesModel::class);
    }

    public function etapeMesures()
    {
        return $this->hasMany(EtapeMesure::class);
    }

        protected static function booted()
    {
        static::deleting(function ($etapeMesures) {
            $etapeMesures->etapeMesures()->delete();
        });
    }

    public function getEtapeEnCoursAttribute(): ?string
{
         return $this->etapeMesures
        ->where('is_completed', true)
        ->sortByDesc('id') // ou autre critère logique
        ->first()?->etapeProduction?->nom;
}
}
