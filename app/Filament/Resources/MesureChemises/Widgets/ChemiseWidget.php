<?php

namespace App\Filament\Resources\MesureChemises\Widgets;

use App\Models\MesureChemise;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ChemiseWidget extends StatsOverviewWidget
{
     use InteractsWithPageFilters;

         public static function canView(): bool
{
  
    return Auth::user()->hasRole(['SuperAdmin','AdminAtelier']);
}
     protected function getHeading(): string
    {
        return 'ETAT COUTURE CHEMISE';
    }
    protected function getStats(): array
    {
        $startDate = $this->pageFilters['startDate'] ?? null;
        $endDate = $this->pageFilters['endDate'] ?? null;

        $stats= [
           Stat::make('TOTAL COUTURE', MesureChemise::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->count()),
           Stat::make('TOTAL EN COURS', MesureChemise::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->where('status', 0)
                        ->count()),
           Stat::make('TOTAL TERMINE', MesureChemise::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->where('status', 1)
                        ->count()),
        ];

        $typecouture = MesureChemise::query()
                    ->select(
                    'Type',
                    DB::raw('COUNT(*) as total_count'),
                )
                 ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                ->groupBy('Type')
                ->get();

            foreach ($typecouture as $typecoutur) {
                $stats[] = Stat::make(
                               $typecoutur->Type,
                                 'TOTAL: '.$typecoutur->total_count,
                                )
                               
                                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                              
                        ->color('primary');
                            }

        return $stats;
    
    }
}
