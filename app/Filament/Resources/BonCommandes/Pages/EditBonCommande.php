<?php

namespace App\Filament\Resources\BonCommandes\Pages;

use App\Filament\Resources\BonCommandes\BonCommandeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBonCommande extends EditRecord
{
    protected static string $resource = BonCommandeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
