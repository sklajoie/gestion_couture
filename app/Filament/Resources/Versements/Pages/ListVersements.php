<?php

namespace App\Filament\Resources\Versements\Pages;

use App\Filament\Resources\Versements\VersementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVersements extends ListRecords
{
    protected static string $resource = VersementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
