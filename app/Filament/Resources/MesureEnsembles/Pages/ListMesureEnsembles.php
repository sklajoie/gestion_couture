<?php

namespace App\Filament\Resources\MesureEnsembles\Pages;

use App\Filament\Resources\MesureEnsembles\MesureEnsembleResource;
use App\Filament\Resources\MesureEnsembles\Widgets\EnsembleWidget;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Resources\Pages\ListRecords;


class ListMesureEnsembles extends ListRecords
{
    protected static string $resource = MesureEnsembleResource::class;

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
        EnsembleWidget::class,
    ];
}
}
