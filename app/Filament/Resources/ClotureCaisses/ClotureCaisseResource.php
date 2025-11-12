<?php

namespace App\Filament\Resources\ClotureCaisses;

use App\Filament\Resources\ClotureCaisses\Pages\CreateClotureCaisse;
use App\Filament\Resources\ClotureCaisses\Pages\EditClotureCaisse;
use App\Filament\Resources\ClotureCaisses\Pages\ListClotureCaisses;
use App\Filament\Resources\ClotureCaisses\Pages\ViewClotureCaisse;
use App\Filament\Resources\ClotureCaisses\Schemas\ClotureCaisseForm;
use App\Filament\Resources\ClotureCaisses\Schemas\ClotureCaisseInfolist;
use App\Filament\Resources\ClotureCaisses\Tables\ClotureCaissesTable;
use App\Models\ClotureCaisse;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
class ClotureCaisseResource extends Resource
{
    protected static ?string $model = ClotureCaisse::class;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION CAISSE';
    protected static ?int $navigationSort = 2;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'reference';

    public static function form(Schema $schema): Schema
    {
        return ClotureCaisseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ClotureCaisseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClotureCaissesTable::configure($table);
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
            'index' => ListClotureCaisses::route('/'),
            'create' => CreateClotureCaisse::route('/create'),
            'view' => ViewClotureCaisse::route('/{record}'),
            'edit' => EditClotureCaisse::route('/{record}/edit'),
        ];
    }
}
