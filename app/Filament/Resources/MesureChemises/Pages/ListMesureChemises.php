<?php

namespace App\Filament\Resources\MesureChemises\Pages;

use App\Filament\Resources\MesureChemises\MesureChemiseResource;
use App\Filament\Resources\MesureChemises\Widgets\ChemiseWidget;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Resources\Pages\ListRecords;

class ListMesureChemises extends ListRecords
{
    protected static string $resource = MesureChemiseResource::class;

     use HasFiltersForm;

     public function persistsFiltersInSession(): bool
    {
        return false;
    }
    protected function getHeaderActions(): array
    {
        return [
             FilterAction::make()
                ->schema([
                    DatePicker::make('startDate'),
                    DatePicker::make('endDate'),
                    // ...
                ]),
            CreateAction::make(),
        ];
    }

    
    protected function getHeaderWidgets(): array
{
    return [
        ChemiseWidget::class,
    ];
}
}
