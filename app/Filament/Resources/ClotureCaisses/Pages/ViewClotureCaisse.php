<?php

namespace App\Filament\Resources\ClotureCaisses\Pages;

use App\Filament\Resources\ClotureCaisses\ClotureCaisseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewClotureCaisse extends ViewRecord
{
    protected static string $resource = ClotureCaisseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
