<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MesurePantalon extends Model
{
       protected $fillable = [
         'Reference',
        'Tour_taille',
        'Tour_bassin',
        'Tour_cuisse',
        'Tour_genou',
        'Tour_bas',
        'Hauteur_genou',
        'Hauteur_cheville',
        'Entre_jambe',
        'Longueur_pantalon',
        'Type',
        'Description',
        // 'image_model_id',
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
}
