<?php

namespace App\Filament\Resources\AutreMesures\Pages;

use App\Filament\Resources\AutreMesures\AutreMesureResource;
use App\Models\EtapeMesure;
use App\Models\EtapeProduction;
use App\Models\Produit;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateAutreMesure extends CreateRecord
{
    protected static string $resource = AutreMesureResource::class;




protected function afterCreate(): void
{
        $etapes = $this->form->getState()['etapes'] ?? [];
    //  dd($etapes);
        foreach ($etapes as $etapeData) {
            $isFirst = $etapeData['etape_production_id'] == EtapeProduction::orderBy('id')->first()?->id;

            $dateDebut = $isFirst ? Carbon::now() : $etapeData['date_debut'];
            $dateFin = $isFirst ? Carbon::now()->addMinutes(30) : $etapeData['date_fin'];

            $temp_mis = null;
            if ($dateDebut && $dateFin) {
                $temp_mis = $dateDebut->diff($dateFin);
            }

            EtapeMesure::create([
                'autre_mesure_id' => $this->record->id,
                'etape_production_id' => $etapeData['etape_production_id'],
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'temp_mis' => $temp_mis,
                'comments' => $etapeData['comments'] ?? null,
                'is_completed' => $isFirst ? true : ($etapeData['is_completed'] ?? false),
                'responsable_id' => $isFirst ? Auth::id() : ($etapeData['responsable_id'] ?? null),
                'user_id' => $etapeData['user_id'] ?? Auth::id(),
            ]);


        }
             $this->record->update([
                'etape_id' => 1,
                'status' => 0,
            ]);

$grouped = $this->record->produitCouture->groupBy('produit_id');

foreach ($grouped as $produitId => $details) {
    // Si tu veux décrémenter une seule fois, prends la première quantité
    $quantiteTotale = $details->first()->quantite;

    if ($produit = Produit::find($produitId)) {
        $produit->decrement('stock', $quantiteTotale);
    }
}




}

}
