<?php

namespace App\Filament\Resources\Devis\Widgets;

use App\Models\Agence;
use App\Models\Devis;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DevisStats extends StatsOverviewWidget
{
    // protected string $view = 'filament.resources.devis.widgets.devis-stats';

    protected function getStats(): array
{
    // Statistiques générales
    $stats = [
        
        Stat::make('Total devis', Devis::count()),
        Stat::make('Montant total', number_format(Devis::sum('montant_ttc'), 0, ',', ' ') . ' FCFA'),
        Stat::make('Devis Validés', Devis::where('statut', 'FACTURE')->count())
         ->color('success'),
    ];

    // Nombre de devis par agence
   $agences = Agence::withCount([
        'devis',
        'devis as devis_factures_count' => function ($query) {
            $query->where('statut', 'FACTURE');
        }
    ])->get();

        foreach ($agences as $agence) {
            $stats[] = Stat::make(
                $agence->nom,
                $agence->devis_count . ' devis '
            ) ->description('(' . $agence->devis_factures_count . ' Validés)')
              ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success');
        }

        $stats[]= Stat::make('Devis Utilisateur', Devis::where('user_id',Auth::id())->count());
    return $stats;

}

}
