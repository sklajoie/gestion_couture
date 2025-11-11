<?php

namespace App\Filament\Resources\Ventes\Pages;

use App\Filament\Resources\Ventes\VenteResource;
use App\Models\StockAgence;
use Filament\Resources\Pages\CreateRecord;

class CreateVente extends CreateRecord
{
    protected static string $resource = VenteResource::class;

    
    protected function afterCreate(): void
        {

            $record = $this->record;
            //dd($record->details);

            // foreach ($record->detailVentes as $detail) {
            //     $ligne = StockAgence::where('stock_entreprise_id', $detail->stock_entreprise_id)
            //                             ->where('agence_id', $detail->agence_id)
            //                             ->first();

            //     if ($ligne) {
            //         $quantitevendue = $detail->quantite;
            //         $ligne->decrement('stock', $quantitevendue);
                    
            //     }
            // }
               // Mise à jour du statut de la vente
                $record->update([
                    'statut' => $record->avance >= $record->montant_ttc ? 'SOLDEE' : 'PAS SOLDEE',
                ]);
        }
        

}
