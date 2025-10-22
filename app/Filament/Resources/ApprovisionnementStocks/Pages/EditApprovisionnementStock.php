<?php

namespace App\Filament\Resources\ApprovisionnementStocks\Pages;

use App\Filament\Resources\ApprovisionnementStocks\ApprovisionnementStockResource;
use App\Models\DetailApproStock;
use App\Models\StockEntreprise;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditApprovisionnementStock extends EditRecord
{
    protected static string $resource = ApprovisionnementStockResource::class;

    protected $ancienstock;
    
  protected function beforeSave(): void
{
    $this->ancienstock = DetailApproStock::where('approvisionnement_stock_id', $this->record->id)->get()->keyBy('mesure_id');
 
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
          
            $nouveauxIds = $this->record->detailApproStocks->pluck('mesure_id')->toArray();

            foreach ($this->ancienstock as $ancienneLigne) {
                if (!in_array($ancienneLigne->mesure_id, $nouveauxIds)) {
                    $produit = StockEntreprise::where('reference', $ancienneLigne->reference)->first();

                    if ($produit) {
                        $produit->decrement('stock', $ancienneLigne->quantite);
                    }
                }
            }

            //dd($record);
              $record = $this->record;
            foreach ($record->detailApproStocks as $detail) {
                $type = $detail->mesure_type;
                $mesureId = $detail->mesure_id;

                $modelClass = match ($type) {
                    'chemise' => \App\Models\MesureChemise::class,
                    'robe' => \App\Models\MesureRobe::class,
                    'pantalon' => \App\Models\MesurePantalon::class,
                    'ensemble' => \App\Models\MesureEnsemble::class,
                    default => null,
                };

                $couture = $modelClass ? $modelClass::find($mesureId) : null;
               // dd($couture);
                if ($couture) {
                    $couture->update(['status' => 1]);

                    $quantiteapp = $detail->quantite;
                   $ancienneQuantite = $this->ancienstock[$mesureId]->quantite ?? 0;
                     $delta = $quantiteapp - $ancienneQuantite;

                    $produit = StockEntreprise::where('reference', $couture->Reference)->first();

                    if ($produit) {
                        if ($delta > 0) {
                            $produit->increment('stock', $delta);
                        } else {
                            $produit->decrement('stock', abs($delta));
                        }
                    } else {
                        StockEntreprise::create([
                            'designation' => $couture->Reference,
                            // 'code_barre' => $couture->Reference,
                            'reference' => $couture->Reference,
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
