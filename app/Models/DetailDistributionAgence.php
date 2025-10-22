<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailDistributionAgence extends Model
{
    protected $fillable = [
        'quantite',
        'distribution_agence_id',
        'stock_entreprise_id',
        'user_id',
    ];

    public function distributionAgence()
    {
        return $this->belongsTo(DistributionAgence::class);
    }

    public function stockEntreprise()
    {
        return $this->belongsTo(StockEntreprise::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
