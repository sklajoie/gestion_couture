<?php

namespace App\Filament\Resources\MesurePantalons\Pages;

use App\Filament\Resources\MesurePantalons\MesurePantalonResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMesurePantalons extends ListRecords
{
    protected static string $resource = MesurePantalonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
