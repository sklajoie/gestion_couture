<?php

namespace App\Filament\Resources\Approvisionnements\Pages;

use App\Filament\Resources\Approvisionnements\ApprovisionnementResource;
use App\Models\BonCommande;
use App\Models\DetailBonCommande;
use App\Models\Produit;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditApprovisionnement extends EditRecord
{
    protected static string $resource = ApprovisionnementResource::class;

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
            
            foreach ($record->details as $detail) {
               
                $ligne = DetailBonCommande::where('bon_commande_id', $record->bon_commande_id)
                    ->where('produit_id', $detail->produit_id)
                    ->first();
               // dd( $ligne,$detail->produit_id);
                $produit = Produit::find($detail->produit_id);

                if ($ligne && $produit) {
                    $quantiteCommandee = $ligne->quantite;
                    $quantiteExistante = $ligne->quantite_approvisionnee ?? 0;
                    $quantiteAjoutee = $detail->quantite;
                    $ancienneQuantite = $detail->getOriginal('quantite');
                    $delta = $quantiteAjoutee - $ancienneQuantite;
                    //dd($quantiteExistante,$ancienneQuantite, $quantiteAjoutee, $delta);
                    // Mise à jour du stock selon la différence
                    if ($delta !== 0) {
                        if ($delta > 0) {
                            $produit->increment('stock', $delta);
                        } else {
                            $produit->decrement('stock', abs($delta));
                        }
                    }

                    // Mise à jour de la ligne de commande
                    $nouvelleQuantite = min($quantiteCommandee, $quantiteExistante + $delta);
                    $estComplet = $nouvelleQuantite >= $quantiteCommandee;

                    $ligne->update([
                        'quantite_approvisionnee' => $nouvelleQuantite,
                        'is_approvisionne' => $estComplet,
                    ]);
                }
            }


            $toutesApprovisionnees = DetailBonCommande::where('bon_commande_id', $record->bon_commande_id)
                ->get()
                ->every(fn ($ligne) => $ligne->is_approvisionne);

            $bonCommande = BonCommande::find($record->bon_commande_id);
            if ($bonCommande) {
                $bonCommande->update([
                    'statut' => $toutesApprovisionnees ? 'Approvisionné' : 'Partiellement Approvisionné',
                ]);
            }
        }
}
