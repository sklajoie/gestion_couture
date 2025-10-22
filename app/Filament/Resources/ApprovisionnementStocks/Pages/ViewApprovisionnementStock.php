<?php

namespace App\Filament\Resources\ApprovisionnementStocks\Pages;

use App\Filament\Resources\ApprovisionnementStocks\ApprovisionnementStockResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewApprovisionnementStock extends ViewRecord
{
    protected static string $resource = ApprovisionnementStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
