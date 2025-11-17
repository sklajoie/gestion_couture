<?php

namespace App\Filament\Resources\AutreMesures\Pages;

use App\Filament\Resources\AutreMesures\AutreMesureResource;
use App\Filament\Resources\AutreMesures\Widgets\AutreMesureWidget;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Resources\Pages\ListRecords;

class ListAutreMesures extends ListRecords
{
    protected static string $resource = AutreMesureResource::class;

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
        AutreMesureWidget::class,
    ];
}

}
