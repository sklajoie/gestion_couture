<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransfertAgence extends Model
{
     protected $fillable = [
        'reference',
        'agence_id',
        'agence_destination_id',
        'entreprise_id',
        'etat',
        'statut',
        'user_id',
        'validateur_id',
        'date_transfert',
        'date_validation',
        'detail',
    ];

    public function transfertAgenceDetails()
    {
        return $this->hasMany(TransfertAgenceDetail::class);
    }

    public function agence()    
    {
        return $this->belongsTo(Agence::class);
    }


    public function agenceDestination()    
    {
        return $this->belongsTo(Agence::class, 'agence_destination_id');
    }

    public function entreprise()    
    {
        return $this->belongsTo(Entreprise::class);
    }
    public function user()    
    {
        return $this->belongsTo(User::class);
    }
    public function validateur()    
    {
        return $this->belongsTo(User::class, 'validateur_id');
    }

     
        protected static function booted()
        {
            static::creating(function ($model) {
                
                $now = \Carbon\Carbon::now();
                $prefix = $now->format('ym');

                    // $countItem = TransfertAgence::count() + 1;
                    $countItem = (static::max('id') ?? 0) + 1;
                    $suffix = str_pad($countItem , 4, '0', STR_PAD_LEFT); // ex: 001

                    $numero = "T$prefix{$suffix}"; // ex: 2510001
                    $model->reference =  $numero;
            });
        }

}
