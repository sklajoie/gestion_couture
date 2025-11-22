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
        'caisse_id',
        'montant',
        'mode_paiement',
        'detail',
        'cloture',
        'user_id',
        'entreprise_id',
    ];

    public function vente()
    {
        return $this->belongsTo(Vente::class, 'vente_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function caisse()
    {
        return $this->belongsTo(Caisse::class);
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
                //dd($model);
         
            // Récupérer la vente concernée
            // $vente = Vente::where('id', $model->vente_id)
            //     ->first();
            //     // dd($model, $vente);
            // if ($vente) {
            //     $montantVerse = floatval($model->montant);
            //     $avanceActuelle = floatval($vente->avance ?? 0);
            //     $soldeActuel = floatval($vente->solde ?? 0);

            //     $nouvelleAvance = $avanceActuelle + $montantVerse;
            //     $nouveauSolde = $soldeActuel - $montantVerse;

            //     $vente->update([
            //         'avance' => round($nouvelleAvance, 2),
            //         'solde' => round($nouveauSolde, 2),
            //         'statut' => $nouveauSolde <= 0 ? 'SOLDEE' : 'PAS SOLDEE',
            //     ]);
            //    // dd($model->montant);
            //       Caisse::where('id', $model->caisse_id)->increment('montant',$model->montant);
            // }     
        
                $now = \Carbon\Carbon::now();
                $prefix = $now->format('ym');

                    $countItem = Versement::count() + 1;
                    $suffix = str_pad($countItem , 5, '0', STR_PAD_LEFT); // ex: 001

                    $numero = "VS$prefix{$suffix}"; // ex: 2510001
                    $model->reference =  $numero;

              Caisse::where('id', $model->caisse_id)->increment('montant', $model->montant);
            });

         static::updating(function ($versement) {
                $ancienMontant = $versement->getOriginal('montant') ?? 0;
                $nouveauMontant = $versement->montant ?? 0;

                $delta = floatval($nouveauMontant) - floatval($ancienMontant);
                //dd($ancienMontant, $nouveauMontant, $delta );
                if ($delta != 0) {
                    Caisse::where('id', $versement->caisse_id)->increment('montant', $delta);
                }
                //   static::created(function ($versement) {
                //     Caisse::where('id', $versement->caisse_id)->increment('montant', $versement->montant);
                //     });
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
        // Caisse::where('id', $versement->caisse_id)->decrement('montant',$montant);
    });


        }



}
