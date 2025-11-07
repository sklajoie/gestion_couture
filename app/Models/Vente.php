<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Vente extends Model
{
    // protected $table = 'ventes';

    protected $fillable = [
        'reference',
        'client_id',
        'agence_id',
        'montant_brut',
        'remise',
        'montant_hors_taxe',
        'tva',
        'montant_ttc',
        'avance',
        'solde',
        'statut',
        'cloture',
        'avoire',
        'code_avoir',
        'date_vente',
        'user_id',
    ];

    public function detailVentes()
    {
        return $this->hasMany(DetailVente::class);
    }
    public function versements()
    {
        return $this->hasMany(Versement::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function agence()
    {
        return $this->belongsTo(Agence::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

            protected static function booted()
            {
                // a la creation
                static::creating(function ($model) {

                $model->agence_id = $model->agence_id
                        ?? (Auth::check() ? Auth::user()->agence_id : null)
                        ?? 1;

                    //dd($model);
                    $now = \Carbon\Carbon::now();
                    $prefix = $now->format('ym');

                    do {
                        $suffix = str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
                        $numero = "V{$prefix}{$suffix}";
                    } while (self::where('reference', $numero)->exists());

                    $model->reference = $numero;
                 

                });

            static::deleting(function ($vente) {
                    foreach ($vente->detailVentes as $detail) {
                        $ligneStock = StockAgence::where('stock_entreprise_id', $detail->stock_entreprise_id)
                            ->where('agence_id', $detail->agence_id)
                            ->first();

                        if ($ligneStock) {
                            $ligneStock->increment('stock', $detail->quantite);
                        }
                        }

                    foreach ($vente->versements as $versements) {
                    Caisse::where('id', $versements->caisse_id)->decrement('montant',$versements->montant);
                        }

                // Supprimer les versements liés
                $vente->versements()->delete();
                $vente->detailVentes()->delete();
            });
        }
}
