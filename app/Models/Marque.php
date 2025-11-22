<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marque extends Model
{
        protected $fillable = [
        'nom',
        'user_id',
        'entreprise_id',
    ];
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
