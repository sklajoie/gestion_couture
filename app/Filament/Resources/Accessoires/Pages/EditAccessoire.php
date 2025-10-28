<?php

namespace App\Filament\Resources\Accessoires\Pages;

use App\Filament\Resources\Accessoires\AccessoireResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAccessoire extends EditRecord
{
    protected static string $resource = AccessoireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
