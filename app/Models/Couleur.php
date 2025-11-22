<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Couleur extends Model
{
     protected $fillable = [
        'nom',
        'description',
        'entreprise_id',
    ];
}
