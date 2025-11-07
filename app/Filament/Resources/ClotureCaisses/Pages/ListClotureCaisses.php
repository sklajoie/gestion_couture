<?php

namespace App\Filament\Resources\ClotureCaisses\Pages;

use App\Filament\Resources\ClotureCaisses\ClotureCaisseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClotureCaisses extends ListRecords
{
    protected static string $resource = ClotureCaisseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
