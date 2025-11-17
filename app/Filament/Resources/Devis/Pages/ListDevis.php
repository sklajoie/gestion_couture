<?php

namespace App\Filament\Resources\Devis\Pages;

use App\Filament\Resources\Devis\DevisResource;
use App\Filament\Resources\Devis\Widgets\DevisStats;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListDevis extends ListRecords
{
    protected static string $resource = DevisResource::class;

    
     use HasFiltersForm;

     public function persistsFiltersInSession(): bool
    {
        return false;
    }
    protected function getHeaderActions(): array
    {
        // if (Auth::user()?->agence_id) {
        
        return [
            FilterAction::make()
                ->schema([
                    DatePicker::make('startDate'),
                    DatePicker::make('endDate'),
                    // ...
                ]),
            CreateAction::make(),
        ];
    //        }
    // return [];
           
    }

    protected function getHeaderWidgets(): array
{
    return [
        DevisStats::class,
    ];
}
}
