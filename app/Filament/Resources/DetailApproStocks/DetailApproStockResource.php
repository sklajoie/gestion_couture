<?php

namespace App\Filament\Resources\DetailApproStocks;

use App\Filament\Resources\DetailApproStocks\Pages\CreateDetailApproStock;
use App\Filament\Resources\DetailApproStocks\Pages\EditDetailApproStock;
use App\Filament\Resources\DetailApproStocks\Pages\ListDetailApproStocks;
use App\Filament\Resources\DetailApproStocks\Pages\ViewDetailApproStock;
use App\Filament\Resources\DetailApproStocks\Schemas\DetailApproStockForm;
use App\Filament\Resources\DetailApproStocks\Schemas\DetailApproStockInfolist;
use App\Filament\Resources\DetailApproStocks\Tables\DetailApproStocksTable;
use App\Models\DetailApproStock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DetailApproStockResource extends Resource
{
    protected static ?string $model = DetailApproStock::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    // protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return DetailApproStockForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DetailApproStockInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DetailApproStocksTable::configure($table);
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
            // 'index' => ListDetailApproStocks::route('/'),
            // 'create' => CreateDetailApproStock::route('/create'),
            // 'view' => ViewDetailApproStock::route('/{record}'),
            // 'edit' => EditDetailApproStock::route('/{record}/edit'),
        ];
    }
}
