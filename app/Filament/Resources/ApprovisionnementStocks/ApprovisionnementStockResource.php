<?php

namespace App\Filament\Resources\ApprovisionnementStocks;

use App\Filament\Resources\ApprovisionnementStocks\Pages\CreateApprovisionnementStock;
use App\Filament\Resources\ApprovisionnementStocks\Pages\EditApprovisionnementStock;
use App\Filament\Resources\ApprovisionnementStocks\Pages\ListApprovisionnementStocks;
use App\Filament\Resources\ApprovisionnementStocks\Pages\ViewApprovisionnementStock;
use App\Filament\Resources\ApprovisionnementStocks\Schemas\ApprovisionnementStockForm;
use App\Filament\Resources\ApprovisionnementStocks\Schemas\ApprovisionnementStockInfolist;
use App\Filament\Resources\ApprovisionnementStocks\Tables\ApprovisionnementStocksTable;
use App\Models\ApprovisionnementStock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ApprovisionnementStockResource extends Resource
{
    protected static ?string $model = ApprovisionnementStock::class;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION ENTREPRISES';
    protected static ?int $navigationSort = 3;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'reference';

    public static function form(Schema $schema): Schema
    {
        return ApprovisionnementStockForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ApprovisionnementStockInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApprovisionnementStocksTable::configure($table);
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
            'index' => ListApprovisionnementStocks::route('/'),
            'create' => CreateApprovisionnementStock::route('/create'),
            'view' => ViewApprovisionnementStock::route('/{record}'),
            'edit' => EditApprovisionnementStock::route('/{record}/edit'),
        ];
    }
}
