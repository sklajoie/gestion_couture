<?php

namespace App\Filament\Resources\AutreMesures\Pages;

use App\Filament\Resources\AutreMesures\AutreMesureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAutreMesures extends ListRecords
{
    protected static string $resource = AutreMesureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
