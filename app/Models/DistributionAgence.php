<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionAgence extends Model
{
    protected $fillable = [
        'reference',
        'date_operation',
        'user_id',
        'agence_id',
        'is_valide',
        'validateur_id',
        'entreprise_id',
    ];

    public function agence()
    {
        return $this->belongsTo(Agence::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function validateur()
    {
        return $this->belongsTo(User::class);
    }

    public function detailDistributionAgences()
    {
        return $this->hasMany(DetailDistributionAgence::class);
    }

    
    protected static function booted()
        {
            static::creating(function ($model) {
                $now = \Carbon\Carbon::now();
                $prefix = $now->format('ym');

                    $countItem = DistributionAgence::count() + 1;
                    $suffix = str_pad($countItem , 3, '0', STR_PAD_LEFT); // ex: 001

                    $numero = "D$prefix{$suffix}"; // ex: 2510001
                    $model->reference =  $numero;
            });
        }


}
