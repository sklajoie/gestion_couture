<?php

namespace App\Filament\Resources\Devis;

use App\Filament\Resources\Devis\Pages\CreateDevis;
use App\Filament\Resources\Devis\Pages\EditDevis;
use App\Filament\Resources\Devis\Pages\ListDevis;
use App\Filament\Resources\Devis\Pages\ViewDevis;
use App\Filament\Resources\Devis\Schemas\DevisForm;
use App\Filament\Resources\Devis\Schemas\DevisInfolist;
use App\Filament\Resources\Devis\Tables\DevisTable;
use App\Models\Devis;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
class DevisResource extends Resource
{
    protected static ?string $model = Devis::class;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION VENTES';
    protected static ?int $navigationSort = 3;
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'reference';

    public static function form(Schema $schema): Schema
    {
        return DevisForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DevisInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DevisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDevis::route('/'),
            'create' => CreateDevis::route('/create'),
            'view' => ViewDevis::route('/{record}'),
            'edit' => EditDevis::route('/{record}/edit'),
        ];
    }
}
