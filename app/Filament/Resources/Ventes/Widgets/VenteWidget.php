<?php

namespace App\Filament\Resources\Ventes\Widgets;

use App\Models\Agence;
use App\Models\Vente;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class VenteWidget extends StatsOverviewWidget
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
       $stats = [
        Stat::make('TOTAL VENTE', Vente::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->count()),
        Stat::make('MONTANT TOTAL', number_format(Vente::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->sum('montant_ttc'), 0, ',', ' ' ) . ' FCFA'),
        Stat::make('TOTAL ENCAISSE', number_format(Vente::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->sum('avance'), 0, ',', ' ' ) . ' FCFA'),
        Stat::make('TOTAL A ENCAISSER', number_format(Vente::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->sum('solde'), 0, ',', ' ' ) . ' FCFA'),
        ];

            $agences = Agence::query()
                        ->withCount(['vente' => function ($q) use ($startDate, $endDate) {
                            $q->when($startDate, fn ($q) => $q->whereDate('created_at', '>=', $startDate))
                            ->when($endDate, fn ($q) => $q->whereDate('created_at', '<=', $endDate));
                        }])
                        ->withSum(['vente' => function ($q) use ($startDate, $endDate) {
                            $q->when($startDate, fn ($q) => $q->whereDate('created_at', '>=', $startDate))
                            ->when($endDate, fn ($q) => $q->whereDate('created_at', '<=', $endDate));
                        }], 'montant_ttc')
                        ->withSum(['vente' => function ($q) use ($startDate, $endDate) {
                            $q->when($startDate, fn ($q) => $q->whereDate('created_at', '>=', $startDate))
                            ->when($endDate, fn ($q) => $q->whereDate('created_at', '<=', $endDate));
                        }], 'avance')
                        ->withSum(['vente' => function ($q) use ($startDate, $endDate) {
                            $q->when($startDate, fn ($q) => $q->whereDate('created_at', '>=', $startDate))
                            ->when($endDate, fn ($q) => $q->whereDate('created_at', '<=', $endDate));
                        }], 'solde')
                        // ->withSum('vente', 'montant_ttc')   // somme du montant TTC
                        // ->withSum('vente', 'avance')        // somme de l'avance
                        // ->withSum('vente', 'solde')        // somme de l'avance
                        ->get();

        foreach ($agences as $agence) {
            $stats[] = Stat::make(
                strtoupper($agence->nom),
                $agence->vente_count . ' Vente(s)'
            )
           ->description(
            'MONTANT TOTAL : ' . number_format($agence->vente_sum_montant_ttc ?? 0, 0, ',', ' ') . "  FCFA\n".
            'MONATANT AVANCE: ' . number_format($agence->vente_sum_avance ?? 0, 0, ',', ' ') . "   FCFA\n".
            'MONTANT SOLDE : ' . number_format($agence->vente_sum_solde ?? 0, 0, ',', ' ') . ' FCFA'
        )
        ->extraAttributes(['class' => 'whitespace-pre-line'])

            // ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('primary');
        }

        return $stats;

            

    }
}
