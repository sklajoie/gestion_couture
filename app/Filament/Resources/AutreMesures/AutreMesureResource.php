<?php

namespace App\Filament\Resources\AutreMesures;

use App\Filament\Resources\AutreMesures\Pages\CreateAutreMesure;
use App\Filament\Resources\AutreMesures\Pages\EditAutreMesure;
use App\Filament\Resources\AutreMesures\Pages\ListAutreMesures;
use App\Filament\Resources\AutreMesures\Pages\ViewAutreMesure;
use App\Filament\Resources\AutreMesures\Schemas\AutreMesureForm;
use App\Filament\Resources\AutreMesures\Schemas\AutreMesureInfolist;
use App\Filament\Resources\AutreMesures\Tables\AutreMesuresTable;
use App\Models\AutreMesure;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
class AutreMesureResource extends Resource
{
    protected static ?string $model = AutreMesure::class;

        protected static string | UnitEnum | null $navigationGroup = 'GESTION MESURES';
    protected static ?int $navigationSort = 5;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return AutreMesureForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AutreMesureInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AutreMesuresTable::configure($table);
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
            'index' => ListAutreMesures::route('/'),
            'create' => CreateAutreMesure::route('/create'),
            'view' => ViewAutreMesure::route('/{record}'),
            'edit' => EditAutreMesure::route('/{record}/edit'),
        ];
    }
}
