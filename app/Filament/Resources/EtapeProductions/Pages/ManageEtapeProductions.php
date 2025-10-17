<?php

namespace App\Filament\Resources\EtapeProductions\Pages;

use App\Filament\Resources\EtapeProductions\EtapeProductionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEtapeProductions extends ManageRecords
{
    protected static string $resource = EtapeProductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
