<?php

namespace App\Filament\Resources\MesurePantalons\Pages;

use App\Filament\Resources\MesurePantalons\MesurePantalonResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMesurePantalon extends ViewRecord
{
    protected static string $resource = MesurePantalonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
