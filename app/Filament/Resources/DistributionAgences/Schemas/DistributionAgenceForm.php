<?php

namespace App\Filament\Resources\DistributionAgences\Schemas;

use App\Models\StockEntreprise;
use Closure;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class DistributionAgenceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('agence_id')
                    ->label('Agence')
                    ->relationship('agence', 'nom')
                    ->required(),

                TextInput::make('date_operation')->required()->type('datetime-local'),  
                Hidden::make('user_id')->default(fn () => Auth::id()),
                Toggle::make('is_valide')
                    ->default(false)
                    ->label('Actif')
                    ->onIcon(Heroicon::CheckCircle)
                    ->offIcon(Heroicon::Bolt)
                    ->onColor('success')
                    ->offColor('danger'),

                Repeater::make('detailDistributionAgences')
                    ->label("Détails Distribution Agence")
                     ->relationship('detailDistributionAgences')
                    ->schema([
                        Select::make('stock_entreprise_id')
                                ->label('Stock Entreprise')
                                ->relationship('stockEntreprise', 'code_barre')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->getSearchResultsUsing(fn (string $search): array => 
                        StockEntreprise::query()
                                        ->where(function ($query) use ($search) {
                                            $query->where('code_barre', 'like', "%{$search}%")
                                                ->orWhere('reference', 'like', "%{$search}%")
                                                ->orWhere('prix', 'like', "%{$search}%")
                                                ->orWhere('designation', 'like', "%{$search}%");
                                        })
                                        ->with(['couleur', 'taille']) // important pour éviter les requêtes N+1
                                        
                                        ->get()
                                        ->mapWithKeys(fn ($record) => [
                                            $record->id => "{$record->code_barre} - {$record->reference} - {$record->couleur?->nom} - {$record->taille?->nom} ({$record->prix} XOF)"
                                        ])
                                        ->all()
                                )
                                ->getOptionLabelFromRecordUsing(fn (StockEntreprise $record) => 
                                    "{$record->code_barre} - {$record->reference} - {$record->couleur?->nom} - {$record->taille?->nom} ({$record->prix} XOF)")
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            $produit = StockEntreprise::find($state);
                                            $stock = $produit?->stock ?? 0;
                                            $set('stock', $stock);

                                        }),
                        Hidden::make('stock'),
                        TextInput::make('quantite')
                            ->label('Quantité')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->maxValue(fn (callable $get) => $get('stock'))   
                            ->live(),
                            


                        Hidden::make('user_id')->default(fn () => Auth::id()),
                    ])
                     ->columns(2)
                    ->createItemButtonLabel('Ajouter un produit')
                    ->columnSpanFull(),
            ]);
    }
}
