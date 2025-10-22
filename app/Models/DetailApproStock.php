<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailApproStock extends Model
{
    protected $fillable = [
        'approvisionnement_stock_id',
        'reference',
        'quantite',
        'prix_unitaire',
        'total',
        'mesure_type',
        'mesure_id',
    ];
    public function approvisionnementstock()
    {
        return $this->belongsTo(ApprovisionnementStock::class);
    }
    
        public function mesure()
    {
        return $this->morphTo(__FUNCTION__, 'mesure_type', 'mesure_id');
    }

}
