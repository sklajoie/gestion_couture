<?php

namespace App\Filament\Resources\MesureChemises\Pages;

use App\Filament\Resources\MesureChemises\MesureChemiseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMesureChemise extends ViewRecord
{
    protected static string $resource = MesureChemiseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
