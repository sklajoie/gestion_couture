<?php

namespace App\Filament\Resources\RetourVentes\Pages;

use App\Filament\Resources\RetourVentes\RetourVenteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageRetourVentes extends ManageRecords
{
    protected static string $resource = RetourVenteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
