<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Versement extends Model
{
    // protected $table = 'versements';

    protected $fillable = [
        'reference',
        'vente_id',
        'agence_id',
        'montant',
        'mode_paiement',
        'detail',
        'cloture',
    ];

    public function vente()
    {
        return $this->belongsTo(Vente::class, 'vente_id');
    }

    public function agence()
    {
        return $this->belongsTo(Agence::class, 'agence_id');
    }


          protected static function booted()
        {
            static::creating(function ($model) {
                $model->agence_id = $model->agence_id
                ?? (Auth::check() ? Auth::user()->agence_id : null)
                ?? 1;

         
            // Récupérer la vente concernée
            $vente = Vente::where('id', $model->vente_id)
                ->first();
                // dd($model, $vente);
            if ($vente) {
                $montantVerse = floatval($model->montant);
                $avanceActuelle = floatval($vente->avance ?? 0);
                $soldeActuel = floatval($vente->solde ?? 0);

                $nouvelleAvance = $avanceActuelle + $montantVerse;
                $nouveauSolde = $soldeActuel - $montantVerse;

                $vente->update([
                    'avance' => round($nouvelleAvance, 2),
                    'solde' => round($nouveauSolde, 2),
                    'statut' => $nouveauSolde <= 0 ? 'SOLDEE' : 'PAS SOLDEE',
                ]);
            }     
        
                $now = \Carbon\Carbon::now();
                $prefix = $now->format('ym');

                    $countItem = Versement::count() + 1;
                    $suffix = str_pad($countItem , 5, '0', STR_PAD_LEFT); // ex: 001

                    $numero = "VS$prefix{$suffix}"; // ex: 2510001
                    $model->reference =  $numero;
            });
        
        static::deleting(function ($versement) {
              $vente = Vente::find($versement->vente_id);

        if ($vente) {
            $montant = floatval($versement->montant);
            $nouvelleAvance = floatval($vente->avance) - $montant;
            $nouveauSolde = floatval($vente->solde) + $montant;

            $vente->update([
                'avance' => round($nouvelleAvance, 2),
                'solde' => round($nouveauSolde, 2),
                'statut' => $nouveauSolde <= 0 ? 'SOLDEE' : 'PAS SOLDEE',
            ]);
        }
    });


            static::saved(function ($model) {
            // Récupérer la vente liée
            $vente = \App\Models\Vente::find($model->vente_id);

            if ($vente) {
                // Calcul du delta : différence entre le montant actuel et l'ancien
                $ancienMontant = $model->getOriginal('montant') ?? 0;
                $nouveauMontant = $model->montant ?? 0;
               // dd($nouveauMontant, $ancienMontant);
                $delta = floatval($nouveauMontant) - floatval($ancienMontant);

                // Mise à jour des champs
                $vente->avance = round($vente->avance + $delta, 2);
                $vente->solde = round($vente->solde - $delta, 2);
                $vente->statut = $vente->solde <= 0 ? 'SOLDEE' : 'PAS SOLDEE';

                $vente->save();
            }
        });
        }



}
