<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'nom',
        'telephone',
        'email',
        'adresse',
        'ville',
        'user_id',
        'entreprise_id',
    ];
}
