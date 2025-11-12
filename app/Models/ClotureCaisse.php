<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ClotureCaisse extends Model
{
     protected $fillable = [
        'reference',
        'date',
        'montant_devis',
        'montant_vente',
        'montant_entre',
        'montant_solde',
        'agence_id',
        'user_id'
    ];

        public function agence()
    {
        return $this->belongsTo(Agence::class);
    }

        public function user()
    {
        return $this->belongsTo(User::class);
    }

       protected static function booted()
{
    static::creating(function ($model) {
        // Définir l'agence si elle n'est pas déjà définie
        $model->agence_id = $model->agence_id
            ?? (Auth::check() ? Auth::user()->employe->agence_id : null)
            ?? 1;

        // Générer le préfixe basé sur la date
        $now = \Carbon\Carbon::now();
        $prefix = $now->format('ymd'); // ex: 251104

        // Compter tous les enregistrements existants
        $countItem = static::count() + 1;

        // Générer le suffixe avec padding
        $suffix = str_pad($countItem, 5, '0', STR_PAD_LEFT); // ex: 00001

        // Créer la référence finale
        $model->reference = "{$prefix}{$suffix}"; // ex: 25110400001

        $userId = Auth::id();
        $agenceId = $model->agence_id;
        $reference = $model->reference;

        Vente::whereNull('cloture')
            ->where('user_id', $userId)
            ->where('agence_id', $agenceId)
            ->update(['cloture' => $reference]);
        Versement::whereNull('cloture')
            ->where('user_id', $userId)
            ->where('agence_id', $agenceId)
            ->update(['cloture' => $reference]);

        Devis::whereNull('cloture')
            ->where('user_id', $userId)
            ->where('agence_id', $agenceId)
            ->update(['cloture' => $reference]);
        // $mouvementcaisse = MouvementCaisse::where('structure_type')
        MouvementCaisse::whereNull('cloture')
            ->where('structure_type', "AGENCE")
            ->where('structure_id', $agenceId)
            ->where('user_id', $userId)
            ->update(['cloture' => $reference]);

        // Calcul des totaux
        $model->montant_vente = Vente::where('cloture', $reference)->sum('montant_ttc');
        $model->montant_entre = Vente::where('cloture', $reference)->sum('avance');
        $model->montant_solde = Vente::where('cloture', $reference)->sum('solde');
        $model->montant_devis = Devis::where('cloture', $reference)->sum('montant_ttc');


    });
}

}
