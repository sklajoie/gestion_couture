<?php

namespace App\Filament\Resources\MouvementCaisses\Pages;

use App\Filament\Resources\MouvementCaisses\MouvementCaisseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMouvementCaisses extends ListRecords
{
    protected static string $resource = MouvementCaisseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
