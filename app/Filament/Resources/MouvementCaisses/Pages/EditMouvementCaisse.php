<?php

namespace App\Filament\Resources\MouvementCaisses\Pages;

use App\Filament\Resources\MouvementCaisses\MouvementCaisseResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMouvementCaisse extends EditRecord
{
    protected static string $resource = MouvementCaisseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
