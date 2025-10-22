<?php

namespace App\Filament\Resources\DetailApproStocks\Pages;

use App\Filament\Resources\DetailApproStocks\DetailApproStockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDetailApproStocks extends ListRecords
{
    protected static string $resource = DetailApproStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
