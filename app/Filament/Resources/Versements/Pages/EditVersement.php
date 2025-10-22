<?php

namespace App\Filament\Resources\Versements\Pages;

use App\Filament\Resources\Versements\VersementResource;
use App\Models\Vente;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditVersement extends EditRecord
{
    protected static string $resource = VersementResource::class;
    protected float $ancienMontant = 0;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Sauvegarder l'ancien montant avant mise à jour
        $this->ancienMontant = $this->record->montant ?? 0;
        return $data;
    }

            protected function afterSave(): void
                {

                    $record = $this->record;
                    $ancienrecord = $this->ancienMontant;
                //dd($record ,$ancienrecord);
                    
                    
            // Récupérer la vente concernée
            $vente = Vente::where('id', $record->vente_id)
                ->where('agence_id', $record->agence_id)
                ->first();
//dd($record);
            if ($vente) {
            $delta = floatval($record->montant) - $ancienrecord;

            $nouvelleAvance = floatval($vente->avance) + $delta;
            $nouveauSolde = floatval($vente->solde) - $delta;

            $vente->update([
                'avance' => round($nouvelleAvance, 2),
                'solde' => round($nouveauSolde, 2),
                'statut' => $nouveauSolde <= 0 ? 'SOLDEE' : 'PAS SOLDEE',
            ]);
          }

        }


    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
