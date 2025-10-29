<?php

namespace App\Filament\Resources\Devis\Pages;

use App\Filament\Resources\Devis\DevisResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListDevis extends ListRecords
{
    protected static string $resource = DevisResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()?->agence_id) {
        return [
            CreateAction::make(),
        ];
           }
    return [];
           
    }
}
