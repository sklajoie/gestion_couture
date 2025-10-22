<?php

namespace App\Filament\Resources\DistributionAgences\Pages;

use App\Filament\Resources\DistributionAgences\DistributionAgenceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDistributionAgence extends EditRecord
{
    protected static string $resource = DistributionAgenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
