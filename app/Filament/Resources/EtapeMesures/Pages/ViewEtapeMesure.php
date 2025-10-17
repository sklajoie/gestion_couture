<?php

namespace App\Filament\Resources\EtapeMesures\Pages;

use App\Filament\Resources\EtapeMesures\EtapeMesureResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEtapeMesure extends ViewRecord
{
    protected static string $resource = EtapeMesureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
