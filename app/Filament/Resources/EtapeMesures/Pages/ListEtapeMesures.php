<?php

namespace App\Filament\Resources\EtapeMesures\Pages;

use App\Filament\Resources\EtapeMesures\EtapeMesureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEtapeMesures extends ListRecords
{
    protected static string $resource = EtapeMesureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
