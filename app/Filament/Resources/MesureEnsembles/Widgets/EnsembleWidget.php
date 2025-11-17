<?php

namespace App\Filament\Resources\MesureEnsembles\Widgets;

use App\Models\MesureEnsemble;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class EnsembleWidget extends StatsOverviewWidget
{
      use InteractsWithPageFilters;

          public function getColumns(): int | array
{
    return [
        'md' => 3,
        'xl' => 3,
    ];
}

    protected function getStats(): array
    {
        $startDate = $this->pageFilters['startDate'] ?? null;
        $endDate = $this->pageFilters['endDate'] ?? null;

        $stats= [
           Stat::make('TOTAL COUTURE', MesureEnsemble::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->count()),
           Stat::make('TOTAL EN COURS', MesureEnsemble::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->where('status', 0)
                        ->count()),
           Stat::make('TOTAL TERMINE', MesureEnsemble::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->where('status', 1)
                        ->count()),
        ];

        $typecouture = MesureEnsemble::query()
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
