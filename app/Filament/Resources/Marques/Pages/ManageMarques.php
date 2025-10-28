<?php

namespace App\Filament\Resources\Marques\Pages;

use App\Filament\Resources\Marques\MarqueResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMarques extends ManageRecords
{
    protected static string $resource = MarqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
