<?php

namespace App\Filament\Resources\Couleurs\Pages;

use App\Filament\Resources\Couleurs\CouleurResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCouleurs extends ManageRecords
{
    protected static string $resource = CouleurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
