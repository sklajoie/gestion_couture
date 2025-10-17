<?php

namespace App\Filament\Resources\MesureChemises\Pages;

use App\Filament\Resources\MesureChemises\MesureChemiseResource;
use App\Models\EtapeMesure;
use App\Models\EtapeProduction;
use App\Models\MesureChemise;
use Carbon\Carbon;
// use Filament\Actions\Concerns\HasWizard;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateMesureChemise extends CreateRecord
{
    //  use HasWizard;

    protected static string $resource = MesureChemiseResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
{
   
    $now = Carbon::now();
    $prefix = $now->format('ym'); // Année sur 2 chiffres + mois → ex: 2510

    // Compteur du jour (ex: 001, 002…)
    $countToday = MesureChemise::whereDate('created_at', $now->toDateString())->count() + 1;
    $suffix = str_pad($countToday, 3, '0', STR_PAD_LEFT); // ex: 001

    $data['Reference'] = "CH{$prefix}{$suffix}"; // ex: 2510001

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
                $temp_mis = $dateDebut->diff($dateFin);
            }

            EtapeMesure::create([
                'mesure_chemise_id' => $this->record->id,
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

}


}
