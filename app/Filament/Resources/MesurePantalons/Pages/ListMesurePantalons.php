<?php

namespace App\Filament\Resources\MesurePantalons\Pages;

use App\Filament\Resources\MesurePantalons\MesurePantalonResource;
use App\Filament\Resources\MesurePantalons\Widgets\PantalonWidget;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Resources\Pages\ListRecords;

class ListMesurePantalons extends ListRecords
{
    protected static string $resource = MesurePantalonResource::class;

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
        PantalonWidget::class,
    ];
}

}
