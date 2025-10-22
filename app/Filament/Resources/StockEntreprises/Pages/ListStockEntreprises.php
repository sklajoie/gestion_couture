<?php

namespace App\Filament\Resources\StockEntreprises\Pages;

use App\Filament\Resources\StockEntreprises\StockEntrepriseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockEntreprises extends ListRecords
{
    protected static string $resource = StockEntrepriseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
