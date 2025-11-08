<?php

namespace App\Filament\Resources\MouvementNatures\Pages;

use App\Filament\Resources\MouvementNatures\MouvementNatureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMouvementNatures extends ManageRecords
{
    protected static string $resource = MouvementNatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
