<?php

namespace App\Filament\Resources\StockEntreprises;

use App\Filament\Resources\StockEntreprises\Pages\CreateStockEntreprise;
use App\Filament\Resources\StockEntreprises\Pages\EditStockEntreprise;
use App\Filament\Resources\StockEntreprises\Pages\ListStockEntreprises;
use App\Filament\Resources\StockEntreprises\Pages\ViewStockEntreprise;
use App\Filament\Resources\StockEntreprises\Schemas\StockEntrepriseForm;
use App\Filament\Resources\StockEntreprises\Schemas\StockEntrepriseInfolist;
use App\Filament\Resources\StockEntreprises\Tables\StockEntreprisesTable;
use App\Models\StockEntreprise;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class StockEntrepriseResource extends Resource
{
    protected static ?string $model = StockEntreprise::class;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION ENTREPRISES';
    protected static ?int $navigationSort = 2;
    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-circle-stack";

    // protected static ?string $recordTitleAttribute = 'stockEntreprise.designation';

    public static function form(Schema $schema): Schema
    {
        return StockEntrepriseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StockEntrepriseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockEntreprisesTable::configure($table);
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
            'index' => ListStockEntreprises::route('/'),
            'create' => CreateStockEntreprise::route('/create'),
            'view' => ViewStockEntreprise::route('/{record}'),
            'edit' => EditStockEntreprise::route('/{record}/edit'),
        ];
    }
}
