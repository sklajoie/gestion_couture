<?php

namespace App\Filament\Resources\DistributionAgences\Pages;

use App\Filament\Resources\DistributionAgences\DistributionAgenceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDistributionAgences extends ListRecords
{
    protected static string $resource = DistributionAgenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
