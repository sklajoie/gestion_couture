<?php

namespace App\Filament\Resources\DetailApproStocks\Pages;

use App\Filament\Resources\DetailApproStocks\DetailApproStockResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDetailApproStock extends ViewRecord
{
    protected static string $resource = DetailApproStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
