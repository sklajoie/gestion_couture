<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caisse extends Model
{
       protected $fillable = [
        'nom',
        'montant',
        'agence_id',
        'user_id',
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
}
