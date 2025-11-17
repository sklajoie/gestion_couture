<?php

namespace App\Filament\Resources\Versements\Pages;

use App\Filament\Resources\Versements\VersementResource;
use App\Filament\Resources\Versements\Widgets\VersementWidget;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ListVersements extends ListRecords
{
    protected static string $resource = VersementResource::class;

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
            VersementWidget::class,
        ];
    }
}
