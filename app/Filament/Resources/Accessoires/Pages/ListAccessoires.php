<?php

namespace App\Filament\Resources\Accessoires\Pages;

use App\Filament\Resources\Accessoires\AccessoireResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAccessoires extends ListRecords
{
    protected static string $resource = AccessoireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
