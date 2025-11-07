<?php

namespace App\Filament\Resources\Ventes\Schemas;

use App\Models\Client;
use App\Models\StockAgence;
use App\Models\StockEntreprise;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class VenteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('INFORMATION VENTE')
                        ->schema([

                 Grid::make()
                ->schema([
                    //  Hidden::make('agence_id')
                    //     ->default(fn () => Auth::user()->agence_id),
                    Hidden::make('user_id')
                        ->default(fn () => Auth::id()),
                    Select::make('client_id')
                        ->label('Client')
                        ->relationship('client', 'nom')
                        ->searchable()
                        ->required()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('nom')
                                ->label('Nom')
                                ->required(),
                            TextInput::make('telephone')
                                ->label('Téléphone')
                                ->required(),
                            TextInput::make('email')
                                ->label('Email'),  
                            TextInput::make('ville')
                                ->label('Ville'),      
                            TextInput::make('adresse')
                                ->label('Adresse'),

                            Hidden::make('user_id')
                            ->default(Auth::id()),
                        ])
                    ->createOptionUsing(function (array $data): int {
                        return Client::create($data)->getKey();
                    })
                    ->editOptionForm([
                            TextInput::make('nom')
                                ->label('Nom')
                                ->required(),
                            TextInput::make('telephone')
                                ->label('Téléphone')
                                ->required(),
                            TextInput::make('email')
                                ->label('Email'),
                            TextInput::make('ville')
                                ->label('Ville'),
                            TextInput::make('adresse')
                                ->label('Adresse'),
                                Hidden::make('user_id')
                            ->default(Auth::id()),
                        ])
                        ->updateOptionUsing(function (array $data, Schema $schema) {
                            $schema->getRecord()?->update($data);
                        }),

                    DateTimePicker::make('date_vente')
                        ->columnSpan(1)
                        ->default(now())
                        ->required(),
                    
                          ])->columns(2),

                    Repeater::make('detailVentes')
                        ->relationship()
                        ->schema([
                            Select::make('stock_entreprise_id')
                                ->label('Produits')
                                ->relationship('stockEntreprise', 'code_barre')
                                ->required()
                                ->searchable()
                                ->reactive()
                                ->live()
                                ->preload()
                                ->getSearchResultsUsing(fn (string $search): array => 
                                    StockAgence::query()
                                            ->whereHas('stockEntreprise', fn ($query) => 
                                                    $query->where('code_barre', 'like', "%{$search}%")
                                                        ->orWhere('reference', 'like', "%{$search}%")
                                                        ->orWhere('prix', 'like', "%{$search}%")
                                                        ->orWhere('designation', 'like', "%{$search}%")
                                                )
                                            ->with(['stockEntreprise.couleur', 'stockEntreprise.taille']) // important pour éviter les requêtes N+1
                                            
                                            ->get()
                                            ->mapWithKeys(fn ($record) => [
                                                $record->id => "{$record->code_barre} - {$record->reference} - {$record->couleur?->nom} - {$record->taille?->nom} ({$record->prix} XOF)"
                                            ])
                                            ->all()
                                    )
                                    ->getOptionLabelFromRecordUsing(fn (StockEntreprise $record) => 
                                        "{$record->code_barre} - {$record->reference} - {$record->couleur?->nom} - {$record->taille?->nom} ({$record->prix} XOF)")
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                $produit = StockAgence::find($state);
                                                $stock = $produit?->stock ?? 0;
                                                $prix = $produit?->stockEntreprise?->prix ?? 0;
                                                $qte = floatval($get('quantite') ?? 0);
                                                $total = $qte * $prix;
                                                $set('stock', $stock);
                                                $set('prix_unitaire', $prix);
                                                $set('montant', $total);

                                                  self::calculTotaux($state, $set, $get);

                                            }),
                            Hidden::make('stock'),

                            Hidden::make('agence_id')
                                ->default(fn () => Auth::user()->agence_id),
                            Hidden::make('user_id')
                                ->default(fn () => Auth::id()),

                            TextInput::make('quantite')
                                    ->numeric()
                                    ->default(0)
                                    ->live(onBlur: true)
                                    // ->reactive()
                                    ->minValue(1)
                                    ->maxValue(fn (callable $get) => $get('stock')) 
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $prix = floatval($get('prix_unitaire') ?? 0);
                                        $set('montant', $state * $prix);
                                          self::calculTotaux($state, $set, $get);
                                    }),


                           TextInput::make('prix_unitaire')
                                ->numeric()
                                ->live()
                                ->reactive()
                                ->readOnly()
                                ->required()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $qte = floatval($get('quantite') ?? 0);
                                    $set('montant', $qte * $state);
                                      self::calculTotaux($state, $set, $get);
                                }),


                            TextInput::make('montant')
                                ->numeric()
                                ->required()
                                ->readOnly()
                                ->reactive()
                                 ->live(),
                        ])
                        ->columns(4)
                        ->columnSpanFull()
                        ->live()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                             $details = $get('detailVentes') ?? [];

                            $totalBrut = collect($details)->sum(fn ($item) => floatval($item['quantite'] ?? 0) * floatval($item['prix_unitaire'] ?? 0));
                            $set('montant_brut', round($totalBrut, 2));


                            self::calculTotaux($state, $set, $get);
                        }),
                 ]),
                Step::make('INFORMATION VERSEMENT')
                    ->schema([
                    Section::make('montant')
                        ->schema([
                            TextInput::make('montant_brut')
                                ->label('Montant Brut')
                                ->numeric()
                                ->readOnly()
                                ->reactive()
                                ->live()
                                ->default(0),
                            TextInput::make('remise')
                                ->label('Remise')
                                ->numeric()
                                ->default(0)
                                 ->reactive()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    self::calculTotaux($state, $set, $get);
                                    }),
                            TextInput::make('montant_hors_taxe')
                                ->label('Montant Hors Taxe')
                                ->numeric()
                                ->readOnly()
                                ->default(0)
                                 ->reactive()
                                ->live(),
                            Select::make('tva')
                                ->options([
                                        '0' => '0%',
                                        '0.18' => '18%',
                                    ])
                                ->label('TVA')
                                ->default(0)
                                ->reactive()
                                 ->live()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    self::calculTotaux($state, $set, $get);
                                    }),
                            TextInput::make('montant_ttc')
                                ->label('Montant TTC')
                                ->numeric()
                                ->readOnly()
                                 ->reactive()
                                ->live()
                                ->default(0),
                            TextInput::make('avance')
                                ->label('Avance')
                                ->numeric()
                                ->readOnly()
                                 ->reactive()
                                ->live()
                                ->default(0)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    self::calculTotaux($state, $set, $get);
                                    }),
                            TextInput::make('solde')
                                ->label('Solde')
                                ->numeric()
                                ->default(0)
                                ->readOnly()
                                 ->reactive()
                                ->live(),   
        
                        ]) ->columns(2)->columnSpanFull()
                        ->live()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                             $details = $get('detailVentes') ?? [];

                            $totalBrut = collect($details)->sum(fn ($item) => floatval($item['quantite'] ?? 0) * floatval($item['prix_unitaire'] ?? 0));
                            $set('montant_brut', round($totalBrut, 2));


                            self::calculTotaux($state, $set, $get);
                        }),
                    
                    Section::make('PAIEMENT')
                         ->schema([
                     Repeater::make('versements')
                        ->relationship('versements')
                        ->schema([
                           TextInput::make('montant')
                                ->label('Montant')
                                ->numeric()
                                 ->reactive()
                                ->live(onBlur: true)
                                ->columnSpan(1)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    self::calculTotaux($state, $set, $get);
                                    }),
                            Select::make('mode_paiement')
                                ->label('Mode de Paiement')
                                ->columnSpan(1)
                                ->options([
                                    'Especes' => 'Espèces',
                                    'Mobile Money' => 'Mobile Money',
                                    'Wave' => 'Wave',
                                    'Virement Bancaire' => 'Virement Bancaire',
                                    'Cheque' => 'Chèque',
                                    'Autre' => 'Autre',
                                    'Recouvrement' => 'Recouvrement',
                                ])
                                ->default('Especes')
                                 ->reactive()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    self::calculTotaux($state, $set, $get);
                                    }),
                            Select::make('caisse_id')
                                     ->relationship(
                                        name: 'caisse',
                                        titleAttribute: 'nom',
                                        modifyQueryUsing: fn ($query) => $query->where('agence_id', Auth::user()->agence_id)
                                    )
                                    ->label('Caisse')
                                    ->required()
                                     ->preload(),
                            TextInput::make('detail')
                                 ->label('Detail Versement')
                                 ->columnSpan(2),
                            Hidden::make('agence_id')
                                ->default(fn () => Auth::user()->agence_id),
                            Hidden::make('user_id')
                                ->default(fn () => Auth::id()),
                                
                    ])
                     ->columnSpan(3)
                      ->addActionLabel('Ajouter un versement')
                      ->live()
                        ->reactive()
                        ->default([]) // ← important pour éviter les erreurs si vide
                         ->dehydrated(true)
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            self::calculTotaux($state, $set, $get);
                        }),
                    ])->columnSpanFull()
                 
               
                ]),
                ])->columnSpanFull(),
            ]);
    }

public static function calculTotaux($state, callable $set, callable $get)
{
    // Total brut = somme des montants des lignes de vente
     $details = $get('detailVentes') ?? [];
    $totalBrut = collect($details)->sum(fn ($item) => floatval($item['quantite'] ?? 0) * floatval($item['prix_unitaire'] ?? 0));
    $set('montant_brut', round($totalBrut, 2));
    // Remise
    $remise = floatval($get('remise') ?? 0);
    $totalHT = $totalBrut - $remise;
    $set('montant_hors_taxe', round($totalHT, 2));

    // TVA
    $tva = floatval($get('tva') ?? 0);
    $totalTTC = $totalHT + ($totalHT * $tva);
    $set('montant_ttc', round($totalTTC, 2));

    // Avance = somme des versements
    $versements = $get('versements');
    $avance = collect($versements)
        ->pluck('montant')
        ->filter(fn($v) => is_numeric($v))
        ->map(fn($v) => floatval($v))
        ->sum();
    $set('avance', round($avance, 2));

    // Solde = TTC - avance
    $set('solde', round($totalTTC - $avance, 2));
}


}
