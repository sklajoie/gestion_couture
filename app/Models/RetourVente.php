<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetourVente extends Model
{
       protected $fillable = ['vente_id','montant_total','etat','date_retour','motif','statut','user_id','entreprise_id','agence_id', 'cloture'];

    public function detailsRetourVente()
    {
        return $this->hasMany(DetailRetourVente::class);
    }

    public function vente()
    {
        return $this->belongsTo(Vente::class);
    }
    public function agence()
    {
        return $this->belongsTo(Agence::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
