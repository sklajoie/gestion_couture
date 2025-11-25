<?php

namespace App\Filament\Resources\TransfertAgences\Pages;

use App\Filament\Resources\TransfertAgences\TransfertAgenceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTransfertAgences extends ManageRecords
{
    protected static string $resource = TransfertAgenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
