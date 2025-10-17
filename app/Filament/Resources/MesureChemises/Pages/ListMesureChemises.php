<?php

namespace App\Filament\Resources\MesureChemises\Pages;

use App\Filament\Resources\MesureChemises\MesureChemiseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMesureChemises extends ListRecords
{
    protected static string $resource = MesureChemiseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
