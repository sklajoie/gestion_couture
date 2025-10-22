<?php

namespace App\Filament\Resources\DistributionAgences\Pages;

use App\Filament\Resources\DistributionAgences\DistributionAgenceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDistributionAgence extends ViewRecord
{
    protected static string $resource = DistributionAgenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
