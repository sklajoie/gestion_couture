<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'adresse',
        'poste',
        'date_embauche',
        'user_id',
        'atelier_id',
        'agence_id',
    ];

    public function atelier()
    {
        return $this->belongsTo(Atelier::class);
    }
    public function agence()
    {
        return $this->belongsTo(Agence::class);
    }
    public function etapeMesure()
    {
        return $this->hasMany(EtapeMesure::class);
    }


}
