<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        }



}
