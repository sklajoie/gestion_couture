<?php

namespace App\Filament\Resources\Versements;

use App\Filament\Resources\Versements\Pages\CreateVersement;
use App\Filament\Resources\Versements\Pages\EditVersement;
use App\Filament\Resources\Versements\Pages\ListVersements;
use App\Filament\Resources\Versements\Pages\ViewVersement;
use App\Filament\Resources\Versements\Schemas\VersementForm;
use App\Filament\Resources\Versements\Schemas\VersementInfolist;
use App\Filament\Resources\Versements\Tables\VersementsTable;
use App\Models\Versement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class VersementResource extends Resource
{
    protected static ?string $model = Versement::class;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION VENTES';
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-currency-dollar";

    protected static ?string $recordTitleAttribute = 'reference';

    public static function form(Schema $schema): Schema
    {
        return VersementForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VersementInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VersementsTable::configure($table);
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
            'index' => ListVersements::route('/'),
            'create' => CreateVersement::route('/create'),
            'view' => ViewVersement::route('/{record}'),
            'edit' => EditVersement::route('/{record}/edit'),
        ];
    }
}
