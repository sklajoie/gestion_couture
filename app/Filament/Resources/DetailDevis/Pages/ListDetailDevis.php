<?php

namespace App\Filament\Resources\DetailDevis\Pages;

use App\Filament\Resources\DetailDevis\DetailDevisResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDetailDevis extends ListRecords
{
    protected static string $resource = DetailDevisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
