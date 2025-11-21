<?php

namespace App\Filament\Resources\Versements\Widgets;

use App\Models\Agence;
use App\Models\Versement;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VersementWidget extends StatsOverviewWidget
{
     use InteractsWithPageFilters;
     
         public static function canView(): bool
{
  
    return Auth::user()->hasRole(['AdminAgence', 'SuperAdmin']);
}
     protected function getHeading(): string
    {
        return 'ETAT DES VERSEMENTS';
    }
    
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
        $stats =  [
       // Stat::make('Montant Total', number_format(Versement::sum('montant'), 0, ',', ' ') . ' FCFA'),
        Stat::make(
                label: 'MONTANT TOTAL',
                value: number_format(
                    Versement::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->sum('montant'), 0, ',', ' ' ) . ' Fcfa'
                )

        //  ->color('success'),
        // Stat::make('Montant à Encaissé', number_format(Vente::sum('solde'), 0, ',', ' ') . ' FCFA')
        //  ->color('danger'),
        ];

            $agences = Agence::query()
                        ->withCount(['versement' => function ($q) use ($startDate, $endDate) {
                            $q->when($startDate, fn ($q) => $q->whereDate('created_at', '>=', $startDate))
                            ->when($endDate, fn ($q) => $q->whereDate('created_at', '<=', $endDate));
                        }])
                        ->withSum(['versement' => function ($q) use ($startDate, $endDate) {
                            $q->when($startDate, fn ($q) => $q->whereDate('created_at', '>=', $startDate))
                            ->when($endDate, fn ($q) => $q->whereDate('created_at', '<=', $endDate));
                        }], 'montant')
                        ->get();

        foreach ($agences as $agence) {
            $stats[] = Stat::make(
                strtoupper($agence->nom),
                $agence->versement_count . ' Vst(s)'
            )
           ->description(
            'MONTANT:'. "\n" . number_format($agence->versement_sum_montant ?? 0, 0, ',', ' ') . ' FCFA'
        )
            // ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success');
        }

        $versements = Versement::query()
                    ->select(
                    'mode_paiement',
                    DB::raw('COUNT(*) as total_count'),
                    DB::raw('SUM(montant) as total_montant')
                )
                 ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                ->groupBy('mode_paiement')
                ->get();

            foreach ($versements as $versement) {
                $stats[] = Stat::make(
                                strtoupper($versement->mode_paiement),
                                $versement->total_count . ' N'
                                )
                                ->description(
                                    'Montant: ' . number_format($versement->total_montant ?? 0, 0, ',', ' ') . ' FCFA'
                                )
                                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                                ->color('warning');
                            }

        return $stats;
    }
}
