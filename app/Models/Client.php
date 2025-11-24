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
        'code_client',
    ];

    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

           protected static function booted()
{

    static::creating(function ($model) {
        // Récupérer le prochain ID
        $nextId = (static::max('id') ?? 0) + 1;

        // Générer le code avec padding
        $model->code_client = "C".str_pad($nextId, 4, '0', STR_PAD_LEFT);
    });


}
}
