<?php

namespace App\Filament\Resources\TransfertAgences;

use BackedEnum;
use App\Models\Agence;
use Filament\Tables\Table;
use App\Models\StockAgence;
use Filament\Schemas\Schema;
use App\Models\StockEntreprise;
use App\Models\TransfertAgence;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use function Laravel\Prompts\select;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;

use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use App\Filament\Resources\TransfertAgences\Pages\ManageTransfertAgences;

class TransfertAgenceResource extends Resource
{
    protected static ?string $model = TransfertAgence::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
            Grid::make()
                ->schema([
               Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
                Hidden::make('entreprise_id')
                     ->default(fn () => Auth::user()->entreprise_id),
                Hidden::make('agence_id')
                     ->default(fn () => Auth::user()->employe?->agence_id),
                     
               Select::make('agence_destination_id')
                    ->label('Agence de destination')
                    ->options(
                        Agence::query()
                    ->where('id', '!=', Auth::user()->employe?->agence_id)
                    ->pluck('nom', 'id'))
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->preload(),
                DatePicker::make('date_transfert')
                    ->required()
                    ->default(now()),
                Select::make('statut')
                    ->options([
                        'En cours' => 'En cours',
                        'Transféré' => 'Transféré',
                    ])->default('Transféré'),
                Textarea::make('detail')
                    ->label('Détails'),
            ])
            ->columns(2)
            ->columnSpanFull(),
        Grid::make()
             ->schema([

                
                    Repeater::make('transfertAgenceDetails')
                         ->label('Produits à transférer')
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
                                                $set('stock', $stock);

                                            }),
                            Hidden::make('stock'),
                            TextInput::make('quantite')
                                    ->numeric()
                                    ->default(0)
                                    ->live(onBlur: true)
                                    // ->reactive()
                                    ->minValue(1)
                                    ->maxValue(fn (callable $get) => $get('stock')) 
                                    ->required(),
                        ])
                        ->columns(2)
                        ->columnSpanFull()
                        
                        ->live()
                        ->reactive()
                    ])->columnSpanFull(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('reference')
                    ->label('Référence'),
                TextEntry::make('agence.nom')
                    ->label('Agence'),
                TextEntry::make('agence_destination.nom')
                    ->label('Agence Destination'),
                IconEntry::make('etat')
                    ->boolean(),
                TextEntry::make('user.name')
                    ->label('Utilisateur'),
                TextEntry::make('validateur.name')
                    ->label('Validateur')
                    ->placeholder('-'),
                TextEntry::make('date_transfert')
                    ->date('d M Y'),
                TextEntry::make('date_validation')
                    ->date('d M Y')
                    ->placeholder('-'),
                TextEntry::make('statut')
                    ->placeholder('-'),
                TextEntry::make('detail')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),

                RepeatableEntry::make('transfertAgenceDetails')
                        ->columnSpanFull()
                        ->table([
                    TableColumn::make('PRODUIT'),
                    TableColumn::make('QTE'),
                        ])
                        ->schema([
                    TextEntry::make('stockEntreprise.designation'),
                    TextEntry::make('quantite'),
                         ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                 TextColumn::make('date_transfert')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('reference')
                    ->label('Référence')
                    ->sortable(),
                TextColumn::make('agence.nom')
                    ->label('Agence')
                    ->sortable(),
                TextColumn::make('agenceDestination.nom')
                    ->label('Agence Destination')
                    ->sortable(),
                ToggleColumn::make('etat')
                    ->label('État')
                    ->sortable()
                    ->disabled(fn ($record) => $record->agence_destination_id !== Auth::user()->employe?->agence_id || $record->etat )
                    ->afterStateUpdated(function ($state, $record) {
                        $record->update(['etat' => $state, 'statut' => $state ? 'Receptionné' : 'En Cours']);
                        //dd( $record->detailsRetourVente);
                        foreach ($record->transfertAgenceDetails as $detail) {
                            $stockAgence = StockAgence::where('agence_id', $record->agence_id)
                                ->where('stock_entreprise_id', $detail->stock_entreprise_id)
                                ->first();
                            $stockDestination = StockAgence::where('agence_id', $record->agence_destination_id)
                                ->where('stock_entreprise_id', $detail->stock_entreprise_id)
                                ->first();

                            if ($stockAgence && $stockDestination) {
                                // Met à jour le stock en ajoutant la quantité retournée
                                $stockAgence->decrement('stock', $detail->quantite);
                                $stockDestination->increment('stock', $detail->quantite);
                            }
                        }
                        $record->update(['date_validation' => $state ? now() : null, 'validateur_id' => $state ? Auth::id() : null]);
                        Notification::make()
                            ->title('Transfert Validé avec succès')
                            ->success()
                            ->send();
                    }),
                TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->sortable(),
                TextColumn::make('validateur.name')
                    ->label('Validateur')
                    ->sortable(),
               
                TextColumn::make('date_validation')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('statut')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => ManageTransfertAgences::route('/'),
        ];
    }
}
