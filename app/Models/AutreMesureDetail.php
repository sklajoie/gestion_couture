<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutreMesureDetail extends Model
{
     protected $fillable = [
        'nom',
        'mesure',
        'autre_mesure_id',
        'detail',
        'entreprise_id',
    ];

      public function autreMesure()
    {
        return $this->belongsTo(AutreMesure::class);
    }
}
