<?php

namespace App\Filament\Resources\MesureEnsembles\Pages;

use App\Filament\Resources\MesureEnsembles\MesureEnsembleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMesureEnsembles extends ListRecords
{
    protected static string $resource = MesureEnsembleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
