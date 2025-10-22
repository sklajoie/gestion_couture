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



            protected function mutateFormDataBeforeCreate(array $data): array
        {
            $now = Carbon::now();
            $nowmonth = $now->format('m');
            $prefix = $now->format('ym'); // Année sur 2 chiffres + mois → ex: 2510

            // Compteur du jour (ex: 001, 002…)
            $countToday = ApprovisionnementStock::count() + 1;
            $suffix = str_pad($countToday, 3, '0', STR_PAD_LEFT); // ex: 001

            $data['reference'] = "AS{$prefix}{$suffix}"; // ex: 2510001
            return $data;
        }

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
                    default => null,
                };

                $couture = $modelClass ? $modelClass::find($mesureId) : null;
               // dd($couture);
                if ($couture) {
                    $couture->update(['status' => 1]);

                    $quantiteapp = $detail->quantite;

                    $produit = StockEntreprise::where('reference', $couture->Reference)->first();

                    if ($produit) {
                        $produit->increment('stock', $quantiteapp);
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
