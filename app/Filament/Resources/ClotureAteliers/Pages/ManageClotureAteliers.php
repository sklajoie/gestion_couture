<?php

namespace App\Filament\Resources\ClotureAteliers\Pages;

use App\Filament\Resources\ClotureAteliers\ClotureAtelierResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageClotureAteliers extends ManageRecords
{
    protected static string $resource = ClotureAtelierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
