<?php

namespace App\Filament\Resources\Ventes\Pages;

use App\Filament\Resources\Ventes\VenteResource;
use App\Filament\Resources\Ventes\Widgets\VenteWidget;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Resources\Pages\ListRecords;

class ListVentes extends ListRecords
{
    protected static string $resource = VenteResource::class;

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
        VenteWidget::class,
    ];
}
}
