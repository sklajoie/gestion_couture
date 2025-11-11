<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EtapeAtelier extends Model
{
        protected $fillable = [
            'mesure_type',
            'mesure_id',
            'etape_production_id',
            'employe_id',
            'date',
            'atelier_id',
            'user_id',
        ];

         public function mesure()
    {
        return $this->morphTo(__FUNCTION__, 'mesure_type', 'mesure_id');
    }

        public function etapeProduction()
    {
        return $this->belongsTo(EtapeProduction::class, 'etape_production_id');
    }
    public function responsable()
    {
        return $this->belongsTo(Employe::class, 'employe_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function atelier()
    {
        return $this->belongsTo(Atelier::class);
    }

}
