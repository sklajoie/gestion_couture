<?php

namespace App\Filament\Resources\DetailDevis;

use App\Filament\Resources\DetailDevis\Pages\CreateDetailDevis;
use App\Filament\Resources\DetailDevis\Pages\EditDetailDevis;
use App\Filament\Resources\DetailDevis\Pages\ListDetailDevis;
use App\Filament\Resources\DetailDevis\Pages\ViewDetailDevis;
use App\Filament\Resources\DetailDevis\Schemas\DetailDevisForm;
use App\Filament\Resources\DetailDevis\Schemas\DetailDevisInfolist;
use App\Filament\Resources\DetailDevis\Tables\DetailDevisTable;
use App\Models\DetailDevis;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DetailDevisResource extends Resource
{
    protected static ?string $model = DetailDevis::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return DetailDevisForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DetailDevisInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DetailDevisTable::configure($table);
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
            'index' => ListDetailDevis::route('/'),
            'create' => CreateDetailDevis::route('/create'),
            'view' => ViewDetailDevis::route('/{record}'),
            'edit' => EditDetailDevis::route('/{record}/edit'),
        ];
    }
}
