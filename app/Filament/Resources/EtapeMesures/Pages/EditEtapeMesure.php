<?php

namespace App\Filament\Resources\EtapeMesures\Pages;

use App\Filament\Resources\EtapeMesures\EtapeMesureResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEtapeMesure extends EditRecord
{
    protected static string $resource = EtapeMesureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
