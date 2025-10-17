<?php

namespace App\Filament\Resources\MesureEnsembles\Pages;

use App\Filament\Resources\MesureEnsembles\MesureEnsembleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMesureEnsemble extends ViewRecord
{
    protected static string $resource = MesureEnsembleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
