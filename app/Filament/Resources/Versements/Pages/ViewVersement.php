<?php

namespace App\Filament\Resources\Versements\Pages;

use App\Filament\Resources\Versements\VersementResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVersement extends ViewRecord
{
    protected static string $resource = VersementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
