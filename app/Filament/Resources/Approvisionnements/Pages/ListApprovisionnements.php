<?php

namespace App\Filament\Resources\Approvisionnements\Pages;

use App\Filament\Resources\Approvisionnements\ApprovisionnementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApprovisionnements extends ListRecords
{
    protected static string $resource = ApprovisionnementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
