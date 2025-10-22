<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAgence extends Model
{
    protected $fillable = [
        'stock_alerte',
        'stock',
        'stock_entreprise_id',
        'agence_id',
        'user_id',
    ];

    public function stockEntreprise()
    {
        return $this->belongsTo(StockEntreprise::class);
    }
    public function agence()
    {
        return $this->belongsTo(Agence::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}
