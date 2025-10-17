<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagesModel extends Model
{
    protected $fillable = [
        'models_couture',
    ];

    public function mesureEnsembles()
    {
        return $this->hasMany(MesureEnsemble::class);
    }
    public function mesureRobes()
    {
        return $this->hasMany(MesureRobe::class);
    }
    public function mesureChemises()
    {
        return $this->hasMany(MesureChemise::class);
    }
    public function mesurePantalons()
    {
        return $this->hasMany(MesurePantalon::class);
    }
}
