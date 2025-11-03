<?php

namespace App\Filament\Resources\Accessoires;

use App\Filament\Resources\Accessoires\Pages\CreateAccessoire;
use App\Filament\Resources\Accessoires\Pages\EditAccessoire;
use App\Filament\Resources\Accessoires\Pages\ListAccessoires;
use App\Filament\Resources\Accessoires\Pages\ViewAccessoire;
use App\Filament\Resources\Accessoires\Schemas\AccessoireForm;
use App\Filament\Resources\Accessoires\Schemas\AccessoireInfolist;
use App\Filament\Resources\Accessoires\Tables\AccessoiresTable;
use App\Models\Accessoire;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
class AccessoireResource extends Resource
{
    protected static ?string $model = Accessoire::class;

        protected static string | UnitEnum | null $navigationGroup = 'GESTION STOCK';
    protected static ?int $navigationSort = 9;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return AccessoireForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AccessoireInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AccessoiresTable::configure($table);
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
            'index' => ListAccessoires::route('/'),
            'create' => CreateAccessoire::route('/create'),
            'view' => ViewAccessoire::route('/{record}'),
            'edit' => EditAccessoire::route('/{record}/edit'),
        ];
    }
}
