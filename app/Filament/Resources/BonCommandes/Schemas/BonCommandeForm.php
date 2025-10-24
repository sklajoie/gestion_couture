<?php

namespace App\Filament\Resources\BonCommandes\Schemas;

use App\Models\Fournisseur;
use App\Models\Produit;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;

class BonCommandeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               
                Select::make('fournisseur_id')
                    ->label('fournisseur')
                    ->searchable()
                    ->required()
                      ->preload()
                    ->getSearchResultsUsing(function (string $search) {
                        return \App\Models\Fournisseur::query()
                            ->where('nom', 'like', "%{$search}%")
                            ->orWhere('telephone', 'like', "%{$search}%")
                            ->orWhere('adresse', 'like', "%{$search}%")
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($fournisseur) {
                                return [
                                    $fournisseur->id => "{$fournisseur->nom} ({$fournisseur->telephone} - {$fournisseur->adresse})",
                                ];
                            })
                            ->toArray();
                    })
                    ->getOptionLabelUsing(function ($value): ?string {
                        $fournisseur = \App\Models\Fournisseur::find($value);
                        return $fournisseur
                            ? "{$fournisseur->nom} ({$fournisseur->telephone} - {$fournisseur->adresse})"
                            : null;
                    }),

                    ////////////choix produit
                    Repeater::make('details')
                        ->label('Produits commandés')
                        ->relationship('details') // Assure-toi que la relation existe dans le modèle BonCommande
                        ->schema([
                            Select::make('produit_id')
                                ->label('Produit')
                                ->relationship('produit', 'nom')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->getOptionLabelFromRecordUsing(fn (Produit $record) => "{$record->nom} ({$record->code_barre})"),
                            TextInput::make('quantite')
                                ->label('Quantité')
                                ->numeric()
                                 ->default(1)
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                  
                                    $prix = floatval($get('prix_unitaire') ?? 0);
                                      $set('total', $state * $prix);
                                   
                                    self::recalcTotals($state, $set, $get);
                            }),
                            // TextInput::make('quantite_approvisionnee')
                            //     ->label('Quantité approvisionnée')
                            //     ->numeric()
                            //     ->default(0)
                            //     ->readOnly(),

                            TextInput::make('prix_unitaire')
                                ->label('Prix unitaire')
                                ->numeric()
                                ->default(0.0)
                                ->required()
                                ->reactive()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $qte = floatval($get('quantite') ?? 0);
                                    $set('total', $state * $qte);
                                    // Recalcul global
                                self::recalcTotals($state, $set, $get);
                                }),
                            TextInput::make('total')
                                ->label('Total')
                                ->numeric()
                                ->default(0.0)
                                ->readOnly()
                                ->reactive(),
                                
                        ])
                        ->columns(4) // Affiche les 4 champs sur une seule ligne
                        ->createItemButtonLabel('Ajouter un produit')
                        ->columnSpanFull()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $details = $get('details') ?? [];

                            $totalBrut = collect($details)->sum(fn ($item) => floatval($item['quantite'] ?? 0) * floatval($item['prix_unitaire'] ?? 0));
                            $set('total_brut', round($totalBrut, 2));

                            // Recalcul complet
                            self::recalcTotals($state,$set, $get);
                        }),
                    /////Choix produit


                   // ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nom} ({$record->telephone} - {$record->adresse})")
                   
                TextInput::make('total_brut')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->readOnly()
                    ->reactive()
                    ->live(),
                TextInput::make('remise')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->reactive()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        // $totalBrut = floatval($get('total_brut') ?? 0);
                        // $set('total_hors_taxes', round(($totalBrut - $state), 2));
                    self::recalcTotals($state, $set, $get);

                    }),
                TextInput::make('total_hors_taxes')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->readOnly()
                    ->reactive()
                    ->live(),
                Select::make('tva')
                    ->options([
                            '0.0' => '0',
                            '0.18' => '18%',
                        ])
                    ->default('0.0')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {

                        self::recalcTotals($state, $set, $get);
                    }),
                TextInput::make('total_ttc')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->readOnly()
                    ->reactive()
                    ->live(),
                TextInput::make('avance')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->reactive()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        self::recalcTotals($state, $set, $get);
                            }),
                TextInput::make('solde')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->readOnly()
                    ->reactive()
                    ->live(),
                Textarea::make('remarques')
                    ->columnSpanFull(),

            

            ]);
    }

    // public static function recalcTotals( $state,callable $set, callable $get)
    // {
    //    $details = $get('../../details') ?? [];
    //     $totalBrut = collect($details)->sum(fn ($item) => floatval($item['total'] ?? 0));
    //     $set('../../total_brut', round($totalBrut, 2));

    //     $remise = floatval($get('remise') ?? 0);
    //     $totalHT = $totalBrut - $remise;
    //     $set('../../total_hors_taxes', round($totalHT, 2));

    //     $tva = floatval($get('tva') ?? 0);
    //     $totalTTC = $totalHT + ($totalHT * $tva);
    //     $set('../../total_ttc', round($totalTTC, 2));

    //     $avance = floatval($get('avance') ?? 0);
    //     $set('../../solde', round($totalTTC - $avance, 2));
    // }

    public static function recalcTotals($state, callable $set, callable $get): void
{
    $details = $get('details') ?? [];
    $totalBrut = collect($details)->sum(fn ($item) => floatval($item['quantite'] ?? 0) * floatval($item['prix_unitaire'] ?? 0));
    $set('total_brut', round($totalBrut, 2));

    $remise = floatval($get('remise') ?? 0);
    $totalHT = $totalBrut - $remise;
    $set('total_hors_taxes', round($totalHT, 2));

    $tva = floatval($get('tva') ?? 0);
    $totalTTC = $totalHT + ($totalHT * $tva);
    $set('total_ttc', round($totalTTC, 2));

    $avance = floatval($get('avance') ?? 0);
    $set('solde', round($totalTTC - $avance, 2));
}


    
}


