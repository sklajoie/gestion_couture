<?php

namespace App\Filament\Resources\MesureRobes\Pages;

use App\Filament\Resources\MesureRobes\MesureRobeResource;
use App\Filament\Resources\MesureRobes\Widgets\RobeWidget;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Resources\Pages\ListRecords;

class ListMesureRobes extends ListRecords
{
    protected static string $resource = MesureRobeResource::class;

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
        RobeWidget::class,
    ];
}

}
