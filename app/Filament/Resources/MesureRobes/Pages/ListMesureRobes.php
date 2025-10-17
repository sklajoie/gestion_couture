<?php

namespace App\Filament\Resources\MesureRobes\Pages;

use App\Filament\Resources\MesureRobes\MesureRobeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMesureRobes extends ListRecords
{
    protected static string $resource = MesureRobeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
