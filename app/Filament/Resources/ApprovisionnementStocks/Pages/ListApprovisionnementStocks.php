<?php

namespace App\Filament\Resources\ApprovisionnementStocks\Pages;

use App\Filament\Resources\ApprovisionnementStocks\ApprovisionnementStockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApprovisionnementStocks extends ListRecords
{
    protected static string $resource = ApprovisionnementStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
