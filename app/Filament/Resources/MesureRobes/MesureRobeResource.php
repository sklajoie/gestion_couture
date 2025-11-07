<?php

namespace App\Filament\Resources\MesureRobes;

use App\Filament\Resources\MesureRobes\Pages\CreateMesureRobe;
use App\Filament\Resources\MesureRobes\Pages\EditMesureRobe;
use App\Filament\Resources\MesureRobes\Pages\ListMesureRobes;
use App\Filament\Resources\MesureRobes\Pages\ViewMesureRobe;
use App\Filament\Resources\MesureRobes\Schemas\MesureRobeForm;
use App\Filament\Resources\MesureRobes\Schemas\MesureRobeInfolist;
use App\Filament\Resources\MesureRobes\Tables\MesureRobesTable;
use App\Models\MesureRobe;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MesureRobeResource extends Resource
{
    protected static ?string $model = MesureRobe::class;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION MESURES';
    protected static ?int $navigationSort = 3;
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Type';

    public static function form(Schema $schema): Schema
    {
        // return MesureRobeForm::configure($schema);
        return MesureRobeForm::configure($schema,$schema->getRecord());
    }

    public static function infolist(Schema $schema): Schema
    {
        return MesureRobeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MesureRobesTable::configure($table);
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
            'index' => ListMesureRobes::route('/'),
            'create' => CreateMesureRobe::route('/create'),
            'view' => ViewMesureRobe::route('/{record}'),
            'edit' => EditMesureRobe::route('/{record}/edit'),
        ];
    }
}
