<?php

namespace App\Filament\Resources\Approvisionnements\Pages;

use App\Filament\Resources\Approvisionnements\ApprovisionnementResource;
use App\Models\Approvisionnement;
use App\Models\BonCommande;
use App\Models\DetailBonCommande;
use App\Models\Produit;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class CreateApprovisionnement extends CreateRecord
{
    protected static string $resource = ApprovisionnementResource::class;

    protected function afterCreate(): void
        {

            $record = $this->record;
            //dd($record->details);

            foreach ($record->details as $detail) {
                $ligne = DetailBonCommande::where('bon_commande_id', $record->bon_commande_id)
                    ->where('produit_id', $detail->produit_id)
                    ->first();
                $produit = Produit::find($detail->produit_id);

                if ($ligne) {
                    $quantiteCommandee = $ligne->quantite;
                    $quantiteExistante = $ligne->quantite_approvisionnee ?? 0;
                    $quantiteAjoutee = $detail->quantite;
                    if($quantiteExistante + $detail->quantite > $quantiteCommandee){
                       $quantiteAjoutee = $quantiteCommandee - $quantiteExistante;
                    }
                    $nouvelleQuantite = min($quantiteCommandee, $quantiteExistante + $quantiteAjoutee);

                    $estComplet = $nouvelleQuantite >= $quantiteCommandee;

                    $ligne->update([
                        'quantite_approvisionnee' => $nouvelleQuantite,
                        'is_approvisionne' => $estComplet,
                    ]);
                    if ($produit) {
                        $produit->increment('stock', $quantiteAjoutee);
                    }
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

       
         protected function afterEdit(): void
        {

            $record = $this->record;
           // dd($record->details);

           foreach ($record->details as $detail) {
                $ligne = DetailBonCommande::where('bon_commande_id', $record->bon_commande_id)
                    ->where('produit_id', $detail->produit_id)
                    ->first();

                $produit = Produit::find($detail->produit_id);

                if ($ligne && $produit) {
                    $quantiteCommandee = $ligne->quantite;
                    $quantiteExistante = $ligne->quantite_approvisionnee ?? 0;
                    $quantiteAjoutee = $detail->quantite;
                    $ancienneQuantite = $detail->getOriginal('quantite');
                    $delta = $quantiteAjoutee - $ancienneQuantite;

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

         protected function mutateFormDataBeforeCreate(array $data): array
          {

            $now = Carbon::now();
            $prefix = $now->format('ym'); // Année sur 2 chiffres + mois → ex: 2510

            // Compteur du jour (ex: 001, 002…)
            $countToday = Approvisionnement::count() + 1;
            $suffix = str_pad($countToday, 3, '0', STR_PAD_LEFT); // ex: 001

            $data['reference'] = "AP{$prefix}{$suffix}"; // ex: 2510001
            $data['user_id'] = Auth::id();
            $data['date_operation'] = date('Y-m-d H:i:s');



            return $data;


          }
}
