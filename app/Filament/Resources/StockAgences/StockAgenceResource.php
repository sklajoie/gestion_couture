<?php

namespace App\Filament\Resources\StockAgences;

use App\Filament\Resources\StockAgences\Pages\ManageStockAgences;
use App\Models\StockAgence;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class StockAgenceResource extends Resource
{
    protected static ?string $model = StockAgence::class;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION AGENCES';
    protected static ?int $navigationSort = 2;
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('stock_alerte')
                ->readOnly(),
                TextInput::make('stock')
                ->readOnly(),
                Select::make('stock_entreprise_id')
                    ->relationship('stockEntreprise', 'reference')
                     ->label('ENTREPÔT')
                    ->searchable()
                     ->preload()
                     ->disabled()
                    ->required(),
                Select::make('agence_id')
                    ->relationship('agence', 'nom')
                    ->label('Agence')
                     ->searchable()
                     ->preload()
                     ->disabled()
                    ->required(),
                Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('stock_alerte')->label('Stock Alerte'),
                TextEntry::make('stock')->label('Stock'),
                TextEntry::make('stockEntreprise.designation')->label('Designation'),
                TextEntry::make('stockEntreprise.code_barre')->label('Code Barre'),
                TextEntry::make('stockEntreprise.reference')->label('Reference'),
                TextEntry::make('stockEntreprise.couleur.nom')->label('Couleur'),
                TextEntry::make('stockEntreprise.taille.nom')->label('Taille'),
                TextEntry::make('agence.nom')->label('Agence'),
                TextEntry::make('user.name')->label('Utilisateur'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Stock Agence')
            ->columns([
                TextColumn::make('stock_alerte')->label('Stock Alerte')->searchable(),
                TextColumn::make('stock')->label('Stock')->searchable(),
                TextColumn::make('stockEntreprise.designation')->label('Designation')->searchable(),
                TextColumn::make('stockEntreprise.code_barre')->label('Code Barre')->searchable(),
                TextColumn::make('stockEntreprise.reference')->label('Reference')->searchable(),
                TextColumn::make('stockEntreprise.couleur.nom')->label('Couleur')->searchable(),
                TextColumn::make('stockEntreprise.taille.nom')->label('Taille')->searchable(),
                TextColumn::make('agence.nom')->label('Agence')->searchable(),
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
            'index' => ManageStockAgences::route('/'),
        ];
    }
}
