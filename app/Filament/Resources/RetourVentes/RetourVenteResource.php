<?php

namespace App\Filament\Resources\RetourVentes;

use App\Filament\Resources\RetourVentes\Pages\ManageRetourVentes;
use App\Models\RetourVente;
use App\Models\StockAgence;
use App\Models\StockEntreprise;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class RetourVenteResource extends Resource
{
    protected static ?string $model = RetourVente::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vente_id')
                    ->label('Référence Vente')
                    ->relationship('vente', 'reference')
                    ->searchable()
                    ->required()
                    ->preload(),
                DatePicker::make('date_retour')
                    ->default(now())
                    ->required(),
               
                Select::make('statut')
                    ->options([
                        'Remboursé' => 'Remboursé',
                        'Remplacé' => 'Remplacé',
                        'Retour Atelier' => 'Retour Atelier',
                    ])
                    ->required(),
                Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
                Hidden::make('entreprise_id')
                     ->default(fn () => Auth::user()->entreprise_id),
                     
                Select::make('agence_id')
                      ->relationship('agence', 'nom')
                        ->required()
                        ->searchable()
                        ->reactive()
                        ->preload()
                        ->default(fn () => Auth::user()->agence_id)
                        ->hidden(fn () => Auth::user()->agence_id !== null),
                TextInput::make('montant_total')
                    ->readOnly(),
                Textarea::make('motif')
                ->label('Motif du Retour'),
                // Toggle::make('etat')
                //     ->required(),
                 Grid::make()
                ->schema([
                    Repeater::make('detailsRetourVente')
                        ->relationship()
                        ->schema([
                            Select::make('stock_entreprise_id')
                                ->label('Produits')
                                ->required()
                                ->searchable()
                                ->reactive()
                                ->live()
                                ->preload()
                                ->options(function (callable $get) {
                                    $agenceId = $get('../../agence_id'); // remonte hors du repeater si nécessaire

                                    return \App\Models\StockAgence::where('agence_id', $agenceId)
                                        ->with(['stockEntreprise.couleur', 'stockEntreprise.taille'])
                                        ->get()
                                        ->mapWithKeys(fn ($record) => [
                                            $record->stock_entreprise_id => "{$record->stockEntreprise->designation} -{$record->stockEntreprise->code_barre} - {$record->stockEntreprise->reference} - {$record->stockEntreprise->couleur?->nom} - {$record->stockEntreprise->taille?->nom} ({$record->stockEntreprise->prix} XOF)"
                                        ])
                                        ->all();
                                })
                                ->getSearchResultsUsing(function (string $search, callable $get): array {
                                        $agenceId = $get('../../agence_id');

                                        return \App\Models\StockAgence::where('agence_id', $agenceId)
                                            ->whereHas('stockEntreprise', fn ($query) =>
                                                $query->where('code_barre', 'like', "%{$search}%")
                                                    ->orWhere('reference', 'like', "%{$search}%")
                                                    ->orWhere('designation', 'like', "%{$search}%")
                                            )
                                            ->with(['stockEntreprise.couleur', 'stockEntreprise.taille'])
                                            ->get()
                                            ->mapWithKeys(fn ($record) => [
                                                $record->stockEntreprise->stock_entreprise_id  => "{$record->stockEntreprise->designation} - {$record->stockEntreprise->reference} - {$record->stockEntreprise->couleur?->nom} - {$record->stockEntreprise->taille?->nom} ({$record->stockEntreprise->prix} XOF)"
                                            ])
                                            ->all();
                                    })

                                    ->getOptionLabelFromRecordUsing(fn (StockEntreprise $record) => 
                                        "{$record->designation} - {$record->reference} - {$record->couleur?->nom} - {$record->taille?->nom} ({$record->prix} XOF)")
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                              $produit = StockAgence::where('stock_entreprise_id', $state)->first();
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

                            // Hidden::make('agence_id')
                            //     ->default(fn () => Auth::user()->agence_id),

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
                             $details = $get('detailsRetourVente') ?? [];

                            $totalBrut = collect($details)->sum(fn ($item) => floatval($item['quantite'] ?? 0) * floatval($item['prix_unitaire'] ?? 0));
                            $set('montant_brut', round($totalBrut, 2));


                            self::calculTotaux($state, $set, $get);
                        }),
                 ])->columnSpanFull(),
            ]);
    }
    
public static function calculTotaux($state, callable $set, callable $get)
{
    // Total brut = somme des montants des lignes de vente
     $details = $get('detailsRetourVente') ?? [];
    $totalBrut = collect($details)->sum(fn ($item) => floatval($item['quantite'] ?? 0) * floatval($item['prix_unitaire'] ?? 0));
    $set('montant_total', round($totalBrut, 2));
    
}

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('vente.reference')
                    ->label('Référence Vente')
                    ->placeholder('-'),
                TextEntry::make('date_retour')
                    ->label('Date de Retour')
                    ->date('d M Y'),
                TextEntry::make('motif')
                    ->label('Motif')
                    ->placeholder('-'),
                TextEntry::make('statut')
                    ->label('Statut')
                    ->placeholder('-'),
                TextEntry::make('user.name')
                    ->label('Utilisateur')
                    ->placeholder('-'),
                TextEntry::make('agence.nom')
                    ->label('Agence')
                    ->placeholder('-'),
                TextEntry::make('montant_total')
                    ->label('Montant Total')
                    ->placeholder('-'),
                IconEntry::make('etat')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('vente.reference')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('date_retour')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('motif')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('statut')
                    ->label('Statut')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->sortable(),
                TextColumn::make('agence.nom')
                    ->label('Agence')
                    ->sortable(),
                TextColumn::make('montant_total')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('etat')
                    ->label('État')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageRetourVentes::route('/'),
        ];
    }
}
