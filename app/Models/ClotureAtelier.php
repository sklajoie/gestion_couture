<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ClotureAtelier extends Model
{
    protected $fillable = [
        'reference',
        'date',
        'montant_total',
        'employe_id',
        'atelier_id',
        'user_id',
    ];

    
        public function atelier()
    {
        return $this->belongsTo(Atelier::class);
    }

        public function employe()
    {
        return $this->belongsTo(Employe::class, 'employe_id');
    }
        public function user()
    {
        return $this->belongsTo(User::class);
    }

       protected static function booted()
{
    static::creating(function ($model) {
        // Définir l'agence si elle n'est pas déjà définie
        $model->atelier_id = $model->atelier_id
            ?? (Auth::check() ? Auth::user()->employe->atelier_id : null)
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

        $userId = $model->employe_id;
        $agenceId = $model->atelier_id;
        $reference = $model->reference;

        // EtapeMesure::whereNull('cloture')
        //     ->where('employe_id', $userId)
        //     ->where('atelier_id', $agenceId)
        //     ->where('is_completed', 1)
        //     ->update(['cloture' => $reference]);

        // // Calcul des totaux
        // $model->montant_total = EtapeMesure::where('cloture', $reference)->sum('montant');

            $etapes = EtapeMesure::whereNull('cloture')
                ->where('employe_id', $userId)
                ->where('atelier_id', $agenceId)
                ->where('is_completed', 1)
                ->get();

            EtapeMesure::whereIn('id', $etapes->pluck('id'))->update(['cloture' => $reference]);

            $model->montant_total = $etapes->sum('montant');

    });
}
}
