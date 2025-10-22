<?php

namespace App\Filament\Resources\StockAgences\Pages;

use App\Filament\Resources\StockAgences\StockAgenceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageStockAgences extends ManageRecords
{
    protected static string $resource = StockAgenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
