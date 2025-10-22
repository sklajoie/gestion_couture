<?php

namespace App\Filament\Resources\StockEntreprises\Pages;

use App\Filament\Resources\StockEntreprises\StockEntrepriseResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditStockEntreprise extends EditRecord
{
    protected static string $resource = StockEntrepriseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
