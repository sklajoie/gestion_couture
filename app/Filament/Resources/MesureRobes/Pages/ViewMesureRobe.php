<?php

namespace App\Filament\Resources\MesureRobes\Pages;

use App\Filament\Resources\MesureRobes\MesureRobeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMesureRobe extends ViewRecord
{
    protected static string $resource = MesureRobeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
