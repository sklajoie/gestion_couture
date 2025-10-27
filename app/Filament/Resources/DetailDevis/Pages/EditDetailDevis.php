<?php

namespace App\Filament\Resources\DetailDevis\Pages;

use App\Filament\Resources\DetailDevis\DetailDevisResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDetailDevis extends EditRecord
{
    protected static string $resource = DetailDevisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
