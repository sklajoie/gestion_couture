<?php

namespace App\Filament\Resources\ApprovisionnementStocks\Pages;

use App\Filament\Resources\ApprovisionnementStocks\ApprovisionnementStockResource;
use App\Models\ApprovisionnementStock;
use App\Models\DetailApproStock;
use App\Models\StockEntreprise;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Auth;

class CreateApprovisionnementStock extends CreateRecord
{
    protected static string $resource = ApprovisionnementStockResource::class;



        //     protected function mutateFormDataBeforeCreate(array $data): array
        // {
           
        //     return $data;
        // }

        protected function afterCreate(): void
        {
            $record = $this->record;
            //dd($record);
            foreach ($record->detailApproStocks as $detail) {
                $type = $detail->mesure_type;
                $mesureId = $detail->mesure_id;

                $modelClass = match ($type) {
                    'chemise' => \App\Models\MesureChemise::class,
                    'robe' => \App\Models\MesureRobe::class,
                    'pantalon' => \App\Models\MesurePantalon::class,
                    'ensemble' => \App\Models\MesureEnsemble::class,
                    'Autre Produit' => \App\Models\Accessoire::class,
                    default => null,
                };

                $couture = $modelClass ? $modelClass::find($mesureId) : null;
               // dd($couture);
                if ($couture) {
                    $couture->update(['status' => 1]);

                    $quantiteapp = $detail->quantite;
                        $designation = !empty($couture->Reference) ? $couture->Reference : $couture->nom;
                        $ref = !empty($couture->Reference) ? $couture->Reference : $couture->code_barre;
                        $produit = StockEntreprise::where('reference', $ref)->first();
                                            // $produit = StockEntreprise::where('reference', $couture->Reference?? $couture->code_barre)->first();

                    if ($produit) {
                        $produit->increment('stock', $quantiteapp);
                    } else {
                        StockEntreprise::create([
                            'designation' => $designation,
                            // 'code_barre' => $couture->Reference,
                            'reference' => $ref,
                            'stock' => $quantiteapp,
                            'prix' => $couture->prix_vente,
                            'stock_alerte' => 1,
                            'couleur_id' => $couture->couleur_id,
                            'taille_id' => $couture->taille_id,
                            'user_id' => Auth::id(),
                        ]);
                    }
                }
            }
        }


}
