<?php

namespace App\Filament\Resources\Ventes\Pages;

use App\Filament\Resources\Ventes\VenteResource;
use App\Models\DetailVente;
use App\Models\StockAgence;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditVente extends EditRecord
{
    protected static string $resource = VenteResource::class;

        protected $anciensDetails;

        protected function beforeSave(): void
        {
            $this->anciensDetails = DetailVente::where('vente_id', $this->record->id)->get();
        }

        
          protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }


        protected function afterSave(): void
                {
                    $record = $this->record;
                    //dd($record->detailVentes);
                    $nouveauxDetails = $record->detailVentes;

                    // // 1. Restaurer le stock des lignes supprimées
                    // foreach ($this->anciensDetails as $ancien) {
                    //     $existeEncore = $nouveauxDetails->first(fn ($d) =>
                    //         $d->stock_entreprise_id === $ancien->stock_entreprise_id &&
                    //         $d->agence_id === $ancien->agence_id
                    //     );

                    //     if (! $existeEncore) {
                    //         $ligneStock = StockAgence::where('stock_entreprise_id', $ancien->stock_entreprise_id)
                    //             ->where('agence_id', $ancien->agence_id)
                    //             ->first();

                    //         $ligneStock?->increment('stock', $ancien->quantite);
                    //     }
                    // }

                    // 2. Ajuster les lignes modifiées ou ajoutées
                    // foreach ($nouveauxDetails as $detail) {
                    //     $ancien = $this->anciensDetails->first(fn ($d) =>
                    //         $d->stock_entreprise_id === $detail->stock_entreprise_id &&
                    //         $d->agence_id === $detail->agence_id
                    //     );

                    //     $ancienQte = $ancien ? $ancien->quantite : 0;
                    //     $delta = $detail->quantite - $ancienQte;

                    //     $ligneStockagence = StockAgence::where('stock_entreprise_id', $detail->stock_entreprise_id)
                    //         ->where('agence_id', $detail->agence_id)
                    //         ->first();

                    //     if ($delta > 0) {
                    //         $ligneStockagence?->decrement('stock', $delta);
                    //     } elseif ($delta < 0) {
                    //         $ligneStockagence?->increment('stock', abs($delta));
                    //     }
                    // }

                    // Mise à jour du statut de la vente
                   $record->update([
                    'statut' => $record->avance >= $record->montant_ttc ? 'SOLDEE' : 'PAS SOLDEE',
                ]);
                }



}
