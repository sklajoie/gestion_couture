<?php

namespace App\Filament\Resources\Ventes;

use App\Filament\Resources\Ventes\Pages\CreateVente;
use App\Filament\Resources\Ventes\Pages\EditVente;
use App\Filament\Resources\Ventes\Pages\ListVentes;
use App\Filament\Resources\Ventes\Pages\ViewVente;
use App\Filament\Resources\Ventes\Schemas\VenteForm;
use App\Filament\Resources\Ventes\Schemas\VenteInfolist;
use App\Filament\Resources\Ventes\Tables\VentesTable;
use App\Models\Vente;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class VenteResource extends Resource
{
    protected static ?string $model = Vente::class;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION VENTES';
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-building-storefront";

    protected static ?string $recordTitleAttribute = 'reference';

    public static function form(Schema $schema): Schema
    {
        return VenteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VenteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VentesTable::configure($table);
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
            'index' => ListVentes::route('/'),
            'create' => CreateVente::route('/create'),
            'view' => ViewVente::route('/{record}'),
            'edit' => EditVente::route('/{record}/edit'),
        ];
    }
}
