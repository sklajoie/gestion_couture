<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EtapeProduction extends Model
{
     protected $fillable = [
        'nom',
        'ordre',
        'description',
        'user_id',
    ];
}
