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
                    ->default(now())
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
                                'autre_mesure' => 'Autre Mesure',
                                'autre_produit' => 'Autre Produit',
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
                            'autre_mesure' => \App\Models\AutreMesure::pluck('reference', 'id'),
                            'accessoire' => \App\Models\Accessoire::all()->mapWithKeys(function ($item) {
                                        return [
                                            $item->id => "{$item->code_barre} - {$item->nom} - {$item->prix_vente} FCFA - {$item->taille?->nom} - {$item->couleur?->nom} -  {$item->marque?->nom}"
                                        ];
                                    }),

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
                            'autre_mesure' => \App\Models\AutreMesure::class,
                            'accessoire' => \App\Models\Accessoire::class,
                            default => null,
                        };

                        $produit = $modelClass ? $modelClass::find($state) : null;

                        $prix =  ($produit?->prix_vente)??  ($produit?->prix_couture)?? 0;
                        $reference = ($produit?->Reference) ?? ($produit?->code_barre )?? 0;
                        $set('prix_unitaire', $prix);
                        $set('reference', $reference);

                        $qte = floatval($produit?->quantite ?? 0);
                        $set('total', $qte * $prix);
                    }),

                Hidden::make('reference'),
                TextInput::make('prix_unitaire')->label('Prix')->numeric()->default(0)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $qte = floatval($get('quantite') ?? 0);
                                        $set('total', $state * $qte);
                                        self::calculTotals($state, $set, $get);
                                    }),
                TextInput::make('quantite')
                        ->numeric()
                        ->default(0)
                        ->minValue(1)
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $prix = floatval($get('prix_unitaire') ?? 0);
                            $set('total', $state * $prix);
                                self::calculTotals($state, $set, $get);
                        }),
               
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
                          $details = $get('detailApproStocks') ?? [] ;
                        $totalAppro = collect($details)->sum(fn ($item) => floatval($item['quantite'] ?? 0) * floatval($item['prix_unitaire'] ?? 0));
                    
                        $set('total_appro', round( $totalAppro, 2));
                        self::calculTotals($state, $set, $get);
                    }),
            ]);
    }


        public static function calculTotals( $state,callable $set, callable $get)
    {
        $details = $get('detailApproStocks') ?? [] ;
        $totalAppro = collect($details)->sum(fn ($item) => floatval($item['quantite'] ?? 0) * floatval($item['prix_unitaire'] ?? 0));
       
        $set('total_appro', round( $totalAppro, 2));
    }
}
