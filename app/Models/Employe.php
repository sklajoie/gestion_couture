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
    ];
}
