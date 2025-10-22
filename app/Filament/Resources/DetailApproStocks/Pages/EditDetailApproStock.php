<?php

namespace App\Filament\Resources\DetailApproStocks\Pages;

use App\Filament\Resources\DetailApproStocks\DetailApproStockResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDetailApproStock extends EditRecord
{
    protected static string $resource = DetailApproStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
