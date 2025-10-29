<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Devis extends Model
{
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
        'date_devis',
        'vente_id',
        'user_id',
    ];

    public function detailDevis()
    {
        
        return $this->hasMany(DetailDevis::class, 'devis_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function vente()
    {
        return $this->belongsTo(Vente::class, 'vente_id');
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

         
            
            
            static::creating(function ($model) {
                
               $model->agence_id = $model->agence_id ?? Auth::user()->agence_id;
        
       // dd($model->toArray()); // pour debug
        // dd($model->agence_id);
                $now = \Carbon\Carbon::now();
                $prefix = $now->format('ym');

                    $countItem = Devis::count() + 1;
                    $suffix = str_pad($countItem , 5, '0', STR_PAD_LEFT); // ex: 001

                    $numero = "D$prefix{$suffix}"; // ex: 2510001
                    $model->reference =  $numero;
            });

    //     static::created(function ($model) {
    //     // Une fois le devis créé, on met à jour les détails
    //    // dd($devis->agence_id);
    //     if ($model->relationLoaded('detailDevis') || $model->detailDevis()->exists()) {
    //         $model->detailDevis()->update(['agence_id' => $model->agence_id]);
    //     }
    //          });


            static::deleting(function ($vente) {
                    foreach ($vente->detailDevis as $detail) {
                        $ligneStock = StockAgence::where('stock_entreprise_id', $detail->stock_entreprise_id)
                            ->where('agence_id', $detail->agence_id)
                            ->first();

                        if ($ligneStock) {
                            $ligneStock->increment('stock', $detail->quantite);
                        }
              }

                // Supprimer les versements liés
                $vente->detailDevis()->delete();
            });
        }
}
