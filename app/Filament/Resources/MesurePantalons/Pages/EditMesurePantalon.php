<?php

namespace App\Filament\Resources\MesurePantalons\Pages;

use App\Filament\Resources\MesurePantalons\MesurePantalonResource;
use App\Models\Produit;
use App\Models\ProduitCouture;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditMesurePantalon extends EditRecord
{
    protected static string $resource = MesurePantalonResource::class;
    protected $ancienCouture;
    
  protected function beforeSave(): void
{
    $this->ancienCouture = ProduitCouture::where('mesure_pantalon_id', $this->record->id)->get()->keyBy('produit_id');
 
}
protected function afterSave(): void
{
    $etapes = $this->form->getState()['etapes'] ?? [];
      $maxId = \App\Models\EtapeProduction::max('id');
      //dd($maxId);
foreach ($etapes as $etapeId => $etapeData) {
    $dateDebut = Carbon::parse($etapeData['date_debut']);
    $dateFin = Carbon::parse($etapeData['date_fin']);

    // Calcul du temps mis
    $temp_mis = null;
    if ($dateDebut && $dateFin) {
        $temp_mis = $dateDebut->diff($dateFin);
    }

    // Récupération de l'étape existante
    $etapeMesure = $this->record->etapeMesures()
        ->where('etape_production_id', $etapeData['etape_production_id'])
        ->first();

    // Mise à jour de la date_fin si l'étape est en cours et vient d'être complétée
    if ($etapeMesure && !$etapeMesure->is_completed && $etapeData['is_completed'] === true) {
        $etapeData['date_fin'] = Carbon::now();
         
               // Mise à jour de la mesure
        if ($etapeData['etape_production_id'] == $maxId) {
            // dd($etapeData['etape_production_id'], $maxId);
            $this->record->update([
                'etape_id' =>   $etapeData['etape_production_id'],
                'status' => 1,
            ]);
        } else {
            $this->record->update([
                'etape_id' =>   $etapeData['etape_production_id'],
                'status' => 0,
            ]);
        }
    }

      if (!$etapeMesure?->atelier_id && !empty($etapeData['atelier_id'])) {

            \App\Models\EtapeAtelier::create([
            'responsable_id'   => $etapeData['responsable_id'],
            'etape_production_id' => $etapeData['etape_production_id'],
            'atelier_id' => $etapeData['atelier_id'],
            'date'       => date(now()), 
            'user_id'    => Auth::id(),
            'mesure_type' => "PANTALON",
            'mesure_id'   => $this->record->id,
            ]);              
    }

    // Mise à jour ou création de l'étape
    $this->record->etapeMesures()->updateOrCreate(
        ['etape_production_id' => $etapeData['etape_production_id']],
        [
            'comments' => $etapeData['comments'] ?? null,
            'is_completed' => $etapeData['is_completed'] ?? false,
            'responsable_id' => $etapeData['responsable_id'] ?? null,
            'date_debut' => $etapeData['date_debut'] ?? null,
            'date_fin' => $etapeData['date_fin'] ?? null,
            'user_id' => $etapeData['user_id'] ?? Auth::id(),
            'temp_mis' => $temp_mis,
            'atelier_id' => $etapeData['atelier_id'] ?? null,
        ]
    );
}


        $nouveauxIds = $this->record->produitCouture->pluck('produit_id')->toArray();

            foreach ($this->ancienCouture as $produitId => $ancienneLigne) {
                if (!in_array($produitId, $nouveauxIds)) {
                    // Ce produit a été supprimé → on remet le stock
                    $produit = Produit::find($produitId);
                    if ($produit) {
                        $produit->increment('stock', $ancienneLigne->quantite);
                       
                    }
                }
            }
            
        foreach ($this->record->produitCouture as $detail) {
            $produitId = $detail->produit_id;
            $nouvelleQuantite = $detail->quantite;
            $ancienneQuantite = $this->ancienCouture[$produitId]->quantite ?? 0;
            //dd($ancienneQuantite, $nouvelleQuantite );
            $delta = $nouvelleQuantite - $ancienneQuantite;

            if ($delta !== 0) {
                $produit = Produit::find($produitId);
                if ($delta > 0) {
                    $produit->decrement('stock', $delta);
                } else {
                    $produit->increment('stock', abs($delta));
                }

               
            }
        }

}


protected function mutateFormDataBeforeFill(array $data): array
{
    $data['etapes'] = $this->record->etapeMesures->mapWithKeys(function ($etape) {
        return [
            $etape->etape_production_id => [
                'etape_production_id' => $etape->etape_production_id,
                'comments' => $etape->comments,
                'is_completed' => $etape->is_completed,
                'responsable_id' => $etape->responsable_id,
                'date_debut' => $etape->date_debut,
                'date_fin' => $etape->date_fin,
                'user_id' => $etape->user_id,
                'temp_mis' => $etape->temp_mis,
                'atelier_id' => $etape->atelier_id,
            ],
        ];
    })->toArray();

    return $data;
}

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
