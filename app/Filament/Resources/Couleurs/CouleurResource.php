<?php

namespace App\Filament\Resources\Couleurs;

use App\Filament\Resources\Couleurs\Pages\ManageCouleurs;
use App\Models\Couleur;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;
class CouleurResource extends Resource
{
    protected static ?string $model = Couleur::class;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION STOCK';
    protected static ?int $navigationSort = 7;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Description'),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nom'),
                TextEntry::make('description'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nom')
            ->columns([
                TextColumn::make('nom')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
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
            'index' => ManageCouleurs::route('/'),
        ];
    }
}
