<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $fillable = [
        'nom',
        'adresse',
        'telephone',
        'email',
        'contact',
        'user_id',
        'entreprise_id',
    ];
}
