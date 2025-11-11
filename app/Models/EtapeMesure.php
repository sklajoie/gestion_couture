<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EtapeMesure extends Model
{
     protected $fillable = [
        'mesure_chemise_id',
        'mesure_robe_id',
        'mesure_pantalon_id',
        'mesure_ensemble_id',
        'autre_mesure_id',
        'etape_production_id',
        'employe_id',
        'is_completed',
        'comments',
        'user_id',
        'atelier_id',
        'date_debut',
        'date_fin',
        'temp_mis',
        'montant',
        'cloture',
    ];
    protected $casts = [
    'date_debut' => 'datetime',
    'date_fin' => 'datetime',
];


    public function mesureChemise()
    {
        return $this->belongsTo(MesureChemise::class, 'mesure_chemise_id');
    }
    public function mesurePantalon()
    {
        return $this->belongsTo(MesurePantalon::class);
    }
    public function mesureRobe()
    {
        return $this->belongsTo(MesureRobe::class);
    }
    public function mesureEnsemble()
    {
        return $this->belongsTo(MesureEnsemble::class);
    }
    public function autreMesure()
    {
        return $this->belongsTo(AutreMesure::class);
    }
    public function etapeProduction()
    {
        return $this->belongsTo(EtapeProduction::class, 'etape_production_id');
    }
    public function responsable()
    {
        return $this->belongsTo(Employe::class, 'employe_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function atelier()
    {
        return $this->belongsTo(Atelier::class);
    }
    

    // protected static function booted()
    // {
    //     static::created(function ($etapeMesure) {
    //         // Vérifie si c’est la première étape
    //       $nombreEtapes = EtapeMesure::where('mesure_chemise_id', $etapeMesure->mesure_chemise_id)->count();

    //         // Si c’est la première, on crée toutes les étapes
    //         if ($nombreEtapes === 1) {
    //         $etapes = EtapeProduction::orderBy('id', 'asc')->get();
    //        // dd($etapes);
    //         foreach ($etapes as $etape) {
    //             EtapeMesure::create([
    //                 'mesure_chemise_id' => $etapeMesure->mesure_chemise_id,
    //                 'etape_production_id' => $etape->id,
    //                 'employe_id' => $etape->id == 1 ? Auth::id() : null,
    //                 'is_completed' => $etape->id == 1,
    //                 'comments' => null,
    //                 'user_id' => Auth::id(),
    //             ]);
    //         }
    //     }

          

    //     });
    
    //     }


    public static function afterCreate(Model $record, array $data): void
{
    foreach ($data['etapes'] ?? [] as $etapeData) {
        EtapeMesure::create([
            'mesure_chemise_id' => $record->id,
            'etape_production_id' => $etapeData['etape_production_id'],
            'comments' => $etapeData['comments'] ?? null,
            'is_completed' => $etapeData['is_completed'] ?? false,
            'employe_id' => $etapeData['employe_id'] ?? null,
            'user_id' => $etapeData['user_id'] ?? Auth::id(),
        ]);
    }
}

    // public function getTempMisAttribute()
    // {
    //     if ($this->date_debut && $this->date_fin) {
    //         return $this->date_debut->diff($this->date_fin)->format('%H:%I:%S');
    //     }

    //     return null;
    // }

}
