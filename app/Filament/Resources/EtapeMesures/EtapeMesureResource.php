<?php

namespace App\Filament\Resources\EtapeMesures;

use App\Filament\Resources\EtapeMesures\Pages\CreateEtapeMesure;
use App\Filament\Resources\EtapeMesures\Pages\EditEtapeMesure;
use App\Filament\Resources\EtapeMesures\Pages\ListEtapeMesures;
use App\Filament\Resources\EtapeMesures\Pages\ViewEtapeMesure;
use App\Filament\Resources\EtapeMesures\Schemas\EtapeMesureForm;
use App\Filament\Resources\EtapeMesures\Schemas\EtapeMesureInfolist;
use App\Filament\Resources\EtapeMesures\Tables\EtapeMesuresTable;
use App\Models\EtapeMesure;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EtapeMesureResource extends Resource
{
    protected static ?string $model = EtapeMesure::class;

    protected static string | UnitEnum | null $navigationGroup = 'RESSOURCES HUMAINES';
    protected static ?int $navigationSort = 7;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return EtapeMesureForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EtapeMesureInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EtapeMesuresTable::configure($table);
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
            'index' => ListEtapeMesures::route('/'),
            'create' => CreateEtapeMesure::route('/create'),
            'view' => ViewEtapeMesure::route('/{record}'),
            'edit' => EditEtapeMesure::route('/{record}/edit'),
        ];
    }
}
