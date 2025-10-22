<?php

namespace App\Filament\Resources\StockEntreprises\Pages;

use App\Filament\Resources\StockEntreprises\StockEntrepriseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStockEntreprise extends ViewRecord
{
    protected static string $resource = StockEntrepriseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
