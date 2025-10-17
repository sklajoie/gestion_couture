<?php

namespace App\Filament\Resources\Approvisionnements\Schemas;

use App\Models\DetailBonCommande;
use App\Models\Produit;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

use function Ramsey\Uuid\v1;

class ApprovisionnementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('bon_commande_id')
                        ->label('Bon de commande')
                        ->relationship('bonCommande', 'reference',
                         fn ($query, $livewire) => $query->where(function ($q) use ($livewire) {
                            $q->where('statut', '!=', 'Approvisionné')
                            ->orWhere('id', $livewire->record?->bon_commande_id);
                        }))
                        ->required()
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if (!$state) return;

                            $details = DetailBonCommande::where('bon_commande_id', $state)->with('produit')->get();

                            $repeaterData = $details->map(function ($detail) {
                                return [
                                    'detail_bon_commande_id' => $detail->id,
                                    'produit_id' => $detail->produit_id,
                                    'produit_nom' => $detail->produit->nom,
                                    'produit_id' => $detail->produit_id,
                                    'quantite' => $detail->quantite,
                                    'quantite_approvisionnee' => $detail->quantite_approvisionnee,
                                    'prix_unitaire' => $detail->prix_unitaire,
                                    'total' => round($detail->quantite * $detail->prix_unitaire, 2),
                                ];
                            })->toArray();

                            $set('details', $repeaterData);
                        }),

                
                      
                Repeater::make('details')
                    ->relationship('details')
                    ->label("Détail de l'approvisionnement")
                    ->schema([

                        Hidden::make('produit_id'),

                        TextInput::make('produit_nom')
                        ->label('Produit')
                        ->readOnly()
                        ->reactive() //  permet de recalculer dynamiquement
                        ->default(fn ($get) => Produit::find($get('produit_id'))?->nom ?? '')
                        ->afterStateHydrated(function (TextInput $component, $state, $record) {
                            if ($record && $record->produit_id) {
                                $component->state(Produit::find($record->produit_id)?->nom ?? '');
                            }
                        }),

                     

                        TextInput::make('quantite')
                            ->label('Quantité')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $quantite = floatval($state ?? 0);
                                $prix = floatval($get('prix_unitaire') ?? 0);
                                $set('total', round($quantite * $prix, 2));
                            }),

                        TextInput::make('prix_unitaire')
                            ->label('Prix unitaire')
                            ->numeric()
                            ->nullable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $prix = floatval($state ?? 0);
                                $quantite = floatval($get('quantite') ?? 0);
                                $set('total', round($quantite * $prix, 2));
                            }),

                        TextInput::make('total')
                            ->label('Total')
                            ->numeric()
                            ->default(0.0)
                            ->readOnly()
                            ->reactive(),
                    ])
                    ->columns(4)
                    ->createItemButtonLabel('Ajouter un produit')
                    ->columnSpanFull()




                
            ]);
    }
}
