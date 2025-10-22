<?php

namespace App\Filament\Resources\DetailApproStocks\Pages;

use App\Filament\Resources\DetailApproStocks\DetailApproStockResource;
use App\Models\StockEntreprise;
use Filament\Resources\Pages\CreateRecord;

class CreateDetailApproStock extends CreateRecord
{
    protected static string $resource = DetailApproStockResource::class;



        //     protected function afterCreate(): void
        // {
        //     $record = $this->record;
        //     //dd($record);
        //     foreach ($record->detailApproStocks as $detail) {
        //         $type = $detail->mesure_type;
        //         $mesureId = $detail->mesure_id;

        //         $modelClass = match ($type) {
        //             'chemise' => \App\Models\MesureChemise::class,
        //             'robe' => \App\Models\MesureRobe::class,
        //             'pantalon' => \App\Models\MesurePantalon::class,
        //             'ensemble' => \App\Models\MesureEnsemble::class,
        //             default => null,
        //         };

        //         $couture = $modelClass ? $modelClass::find($mesureId) : null;
        //        // dd($couture);
        //         if ($couture) {
        //             $couture->update(['status' => 1]);

        //             $quantiteapp = $detail->quantite;

        //             $produit = StockEntreprise::where('reference', $couture->Reference)->first();

        //             if ($produit) {
        //                 $produit->increment('stock', $quantiteapp);
        //             } else {
        //                 StockEntreprise::create([
        //                     'designation' => $couture->Reference,
        //                     // 'code_barre' => $couture->Reference,
        //                     'reference' => $couture->Reference,
        //                     'stock' => $quantiteapp,
        //                     'prix' => $couture->prix_vente,
        //                     'stock_alerte' => 1,
        //                     'couleur_id' => $couture->couleur_id,
        //                     'taille_id' => $couture->taille_id,
        //                 ]);
        //             }
        //         }
        //     }
        // }
}
