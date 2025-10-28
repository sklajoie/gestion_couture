<?php

namespace App\Filament\Resources\Accessoires\Pages;

use App\Filament\Resources\Accessoires\AccessoireResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAccessoire extends ViewRecord
{
    protected static string $resource = AccessoireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
