<?php

namespace App\Filament\Resources\MesurePantalons\Pages;

use App\Filament\Resources\MesurePantalons\MesurePantalonResource;
use App\Models\EtapeMesure;
use App\Models\EtapeProduction;
use App\Models\MesurePantalon;
use App\Models\Produit;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateMesurePantalon extends CreateRecord
{
    protected static string $resource = MesurePantalonResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
{
    $now = Carbon::now();
    $prefix = $now->format('ym'); // Année sur 2 chiffres + mois → ex: 2510

    // Compteur du jour (ex: 001, 002…)
    $countToday = MesurePantalon::whereDate('created_at', $now->toDateString())->count() + 1;
    $suffix = str_pad($countToday, 3, '0', STR_PAD_LEFT); // ex: 001

    $data['Reference'] = "PA{$prefix}{$suffix}"; // ex: 2510001
    return $data;
}

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
                $temp_mis = $dateDebut->diff($dateFin)->format('%H:%I:%S');
            }

            EtapeMesure::create([
                'mesure_pantalon_id' => $this->record->id,
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
