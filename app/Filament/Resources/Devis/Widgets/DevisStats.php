<?php

namespace App\Filament\Resources\Devis\Widgets;

use App\Models\Agence;
use App\Models\Devis;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DevisStats extends StatsOverviewWidget
{
    // protected string $view = 'filament.resources.devis.widgets.devis-stats';

      use InteractsWithPageFilters;
 public function getColumns(): int | array
{
    return [
        'md' => 3,
        'xl' => 2,
    ];
}
    protected function getStats(): array
{
        $startDate = $this->pageFilters['startDate'] ?? null;
        $endDate = $this->pageFilters['endDate'] ?? null;
    // Statistiques générales
    $stats = [
        
        Stat::make('TOTAL DEVIS', Devis::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->count()),
        Stat::make('MONTANT TOTAL', number_format(Devis::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->sum('montant_ttc'), 0, ',', ' ' ) . ' FCFA'),
        Stat::make('DEVIS VALIDE', Devis::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        
                        ->where('statut', 'FACTURE')
                        ->count())
         ->color('success'),
         Stat::make('DEVIS UTILISATEUR', Devis::query()
                        ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                        ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                        ->where('user_id',Auth::id())->count())
    ];

    // Nombre de devis par agence
   $agences = Agence::query()
            ->withCount([
        'devis as devis_count' => function ($q) use ($startDate, $endDate) {
            $q->when($startDate, fn ($q) => $q->whereDate('created_at', '>=', $startDate))
              ->when($endDate, fn ($q) => $q->whereDate('created_at', '<=', $endDate));
        },
        'devis as devis_facture_count' => function ($q) use ($startDate, $endDate) {
            $q->when($startDate, fn ($q) => $q->whereDate('created_at', '>=', $startDate))
              ->when($endDate, fn ($q) => $q->whereDate('created_at', '<=', $endDate))
              ->where('statut', 'FACTURE');
        },
            ])

//    ->withCount([
//         'devis',
//         'devis as devis_factures_count' => function ($query) {
//             $query->where('statut', 'FACTURE');
//         }
//     ])
    
    ->get();

        foreach ($agences as $agence) {
            $stats[] = Stat::make(
                strtoupper($agence->nom),
                $agence->devis_count . ' devis '
            ) ->description('(' . $agence->devis_facture_count . ' Validés)')
              ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success');
        }

       
    return $stats;

}

}
