<?php

namespace App\Filament\Resources\AutreMesures\Pages;

use App\Filament\Resources\AutreMesures\AutreMesureResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAutreMesure extends ViewRecord
{
    protected static string $resource = AutreMesureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
