<?php

namespace App\Filament\Resources\Agences\Pages;

use App\Filament\Resources\Agences\AgenceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAgences extends ManageRecords
{
    protected static string $resource = AgenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
