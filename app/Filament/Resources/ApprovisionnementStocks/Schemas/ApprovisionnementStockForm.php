<?php

namespace App\Filament\Resources\ApprovisionnementStocks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ApprovisionnementStockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(Auth::id()),
                DateTimePicker::make('date_operation')
                    ->label('Date de l\'opération'),
                TextInput::make('total_appro')
                    ->label('Montant Total')
                    ->default(0)
                    ->readOnly(),

                Repeater::make('detailApproStocks')
                    ->label("Détails de l'approvisionnement")
                     ->relationship('detailApproStocks')
                    ->schema([
                        Select::make('mesure_type')
                            ->label('Type de mesure')
                            ->options([
                                'chemise' => 'Chemise',
                                'robe' => 'Robe',
                                'pantalon' => 'Pantalon',
                                'ensemble' => 'Ensemble',
                            ])
                            ->required(),


                    // Champ dynamique selon le type
                Select::make('mesure_id')
                    ->label('Référence')
                    ->options(function (callable $get) {
                        $type = $get('mesure_type');

                        return match ($type) {
                            'chemise' => \App\Models\MesureChemise::pluck('reference', 'id'),
                            'robe' => \App\Models\MesureRobe::pluck('reference', 'id'),
                            'pantalon' => \App\Models\MesurePantalon::pluck('reference', 'id'),
                            'ensemble' => \App\Models\MesureEnsemble::pluck('reference', 'id'),
                            default => [],
                        };
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $type = $get('mesure_type');

                        $modelClass = match ($type) {
                            'chemise' => \App\Models\MesureChemise::class,
                            'robe' => \App\Models\MesureRobe::class,
                            'pantalon' => \App\Models\MesurePantalon::class,
                            'ensemble' => \App\Models\MesureEnsemble::class,
                            default => null,
                        };

                        $produit = $modelClass ? $modelClass::find($state) : null;

                        $prix = $produit?->main_oeuvre ?? 0;
                        $reference = $produit?->Reference ?? 0;
                        $set('prix_unitaire', $prix);
                        $set('reference', $reference);

                        $qte = floatval($produit?->quantite ?? 1);
                        $set('total', $qte * $prix);
                    }),

                Hidden::make('reference'),
                TextInput::make('quantite')
                            ->numeric()
                            ->default(1)
                            ->reactive()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $prix = floatval($get('prix_unitaire') ?? 0);
                                $set('total', $state * $prix);
                                  self::calculTotals($state, $set, $get);
                            }),
                TextInput::make('prix_unitaire')->label('Prix')->numeric()->default(0),
                TextInput::make('total')->label('Total')->numeric()->default(0)->readOnly(),
            ])

                    ->columns(5)
                    ->createItemButtonLabel('Ajouter un Produit')
                    ->minItems(1)
                      ->columnSpanFull()
                    ->required()
                      ->reactive()
                      ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {

                        self::calculTotals($state, $set, $get);
                    }),
            ]);
    }


        public static function calculTotals( $state,callable $set, callable $get)
    {
        $details = $get('detailApproStocks') ;

        $totalcouture = collect($details)
            ->pluck('total')
            ->filter(fn($v) => is_numeric($v))
            ->map(fn($v) => floatval($v))
            ->sum();
        $set('total_appro', round( $totalcouture, 2));
    }
}
