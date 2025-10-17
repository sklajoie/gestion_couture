<?php

namespace App\Filament\Resources\MesureRobes\Pages;

use App\Filament\Resources\MesureRobes\MesureRobeResource;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditMesureRobe extends EditRecord
{
    protected static string $resource = MesureRobeResource::class;

    
protected function afterSave(): void
{
    $etapes = $this->form->getState()['etapes'] ?? [];

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
        ]
    );
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
