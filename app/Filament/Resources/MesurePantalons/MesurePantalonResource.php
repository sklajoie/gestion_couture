<?php

namespace App\Filament\Resources\MesurePantalons;

use App\Filament\Resources\MesurePantalons\Pages\CreateMesurePantalon;
use App\Filament\Resources\MesurePantalons\Pages\EditMesurePantalon;
use App\Filament\Resources\MesurePantalons\Pages\ListMesurePantalons;
use App\Filament\Resources\MesurePantalons\Pages\ViewMesurePantalon;
use App\Filament\Resources\MesurePantalons\Schemas\MesurePantalonForm;
use App\Filament\Resources\MesurePantalons\Schemas\MesurePantalonInfolist;
use App\Filament\Resources\MesurePantalons\Tables\MesurePantalonsTable;
use App\Models\MesurePantalon;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MesurePantalonResource extends Resource
{
    protected static ?string $model = MesurePantalon::class;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION MESURES';
    protected static ?int $navigationSort = 2;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Type';

    public static function form(Schema $schema): Schema
    {
        // return MesurePantalonForm::configure($schema);
         return MesurePantalonForm::configure($schema,$schema->getRecord());
    }

    public static function infolist(Schema $schema): Schema
    {
        return MesurePantalonInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MesurePantalonsTable::configure($table);
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
            'index' => ListMesurePantalons::route('/'),
            'create' => CreateMesurePantalon::route('/create'),
            'view' => ViewMesurePantalon::route('/{record}'),
            'edit' => EditMesurePantalon::route('/{record}/edit'),
        ];
    }
}
