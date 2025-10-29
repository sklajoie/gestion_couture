<?php

namespace App\Filament\Resources\Versements\Pages;

use App\Filament\Resources\Versements\VersementResource;
use App\Models\Vente;
use Filament\Resources\Pages\CreateRecord;

class CreateVersement extends CreateRecord
{
    protected static string $resource = VersementResource::class;


        
        // protected function afterCreate(): void
        // {
        //     $record = $this->record;

        //     // Récupérer la vente concernée
        //     $vente = Vente::where('id', $record->vente_id)
        //         ->first();

        //     if ($vente) {
        //         $montantVerse = floatval($record->montant);
        //         $avanceActuelle = floatval($vente->avance ?? 0);
        //         $soldeActuel = floatval($vente->solde ?? 0);

        //         $nouvelleAvance = $avanceActuelle + $montantVerse;
        //         $nouveauSolde = $soldeActuel - $montantVerse;

        //         $vente->update([
        //             'avance' => round($nouvelleAvance, 2),
        //             'solde' => round($nouveauSolde, 2),
        //             'statut' => $nouveauSolde <= 0 ? 'SOLDEE' : 'PAS SOLDEE',
        //         ]);
        //     }     
        // }
        
}
