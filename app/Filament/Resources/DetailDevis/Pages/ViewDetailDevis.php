<?php

namespace App\Filament\Resources\DetailDevis\Pages;

use App\Filament\Resources\DetailDevis\DetailDevisResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDetailDevis extends ViewRecord
{
    protected static string $resource = DetailDevisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
