<?php

namespace App\Filament\Resources\Ateliers\Pages;

use App\Filament\Resources\Ateliers\AtelierResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAteliers extends ManageRecords
{
    protected static string $resource = AtelierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
