<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MouvementCaisse extends Model
{
     protected $fillable = [
        'reference',
        'type_mouvement',
        'mouvement_nature_id',
        'montant',
        'mode_reglement',
        'structure_type',
        'structure_id',
        'date',
        'caisse_id',
        'employe_id',
        'user_id',
        'cloture',
        'detail',
        'entreprise_id',
    ];

    public function caisse()
    {
        return $this->belongsTo(Caisse::class);
    } 
    public function mouvementNature()
    {
        return $this->belongsTo(MouvementNature::class);
    } 
    public function employe()
    {
        return $this->belongsTo(Employe::class);
    } 
    public function user()
    {
        return $this->belongsTo(User::class);
    } 

         public function structure()
    {
        return $this->morphTo(__FUNCTION__, 'structure_type', 'structure_id');
    }

    
          protected static function booted()
        {
            static::creating(function ($model) {
                   
                $now = \Carbon\Carbon::now();
                $prefix = $now->format('ym');

                    $countItem = MouvementCaisse::count() + 1;
                    $suffix = str_pad($countItem , 5, '0', STR_PAD_LEFT); // ex: 001

                    $numero = "MC$prefix{$suffix}"; // ex: 2510001
                    $model->reference =  $numero;
                 // Mise à jour du montant de la caisse
                if ($model->caisse_id && $model->montant > 0) {
                    if ($model->type_mouvement === 'ENTREE EN CAISSE') {
                        Caisse::where('id', $model->caisse_id)->increment('montant', $model->montant);
                    } elseif ($model->type_mouvement === 'SORTIE DE CAISSE') {
                        Caisse::where('id', $model->caisse_id)->decrement('montant', $model->montant);
                    }
                }
            });

      static::updating(function ($mouvmt) {
            $ancienMontant = $mouvmt->getOriginal('montant') ?? 0;
            $nouveauMontant = $mouvmt->montant ?? 0;

            $delta = floatval($nouveauMontant) - floatval($ancienMontant);

            if ($delta !== 0 && $mouvmt->caisse_id) {
                if ($mouvmt->type_mouvement === 'ENTREE EN CAISSE') {
                    Caisse::where('id', $mouvmt->caisse_id)->increment('montant', $delta);
                } elseif ($mouvmt->type_mouvement === 'SORTIE DE CAISSE') {
                    Caisse::where('id', $mouvmt->caisse_id)->decrement('montant', abs($delta));
                }
            }
        });


         
      static::deleting(function ($mouvmt) {
            if (! $mouvmt->caisse_id || ! $mouvmt->montant) {
                return;
            }

            if ($mouvmt->type_mouvement === 'ENTREE EN CAISSE') {
                Caisse::where('id', $mouvmt->caisse_id)->decrement('montant', $mouvmt->montant);
            } elseif ($mouvmt->type_mouvement === 'SORTIE DE CAISSE') {
                Caisse::where('id', $mouvmt->caisse_id)->increment('montant', $mouvmt->montant);
            }
        });


        }

}
