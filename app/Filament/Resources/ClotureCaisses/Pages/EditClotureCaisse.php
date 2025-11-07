<?php

namespace App\Filament\Resources\ClotureCaisses\Pages;

use App\Filament\Resources\ClotureCaisses\ClotureCaisseResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditClotureCaisse extends EditRecord
{
    protected static string $resource = ClotureCaisseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
