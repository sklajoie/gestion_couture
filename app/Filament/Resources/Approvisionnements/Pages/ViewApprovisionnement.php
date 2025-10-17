<?php

namespace App\Filament\Resources\Approvisionnements\Pages;

use App\Filament\Resources\Approvisionnements\ApprovisionnementResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewApprovisionnement extends ViewRecord
{
    protected static string $resource = ApprovisionnementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
