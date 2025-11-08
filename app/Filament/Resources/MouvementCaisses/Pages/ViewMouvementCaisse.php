<?php

namespace App\Filament\Resources\MouvementCaisses\Pages;

use App\Filament\Resources\MouvementCaisses\MouvementCaisseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMouvementCaisse extends ViewRecord
{
    protected static string $resource = MouvementCaisseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
