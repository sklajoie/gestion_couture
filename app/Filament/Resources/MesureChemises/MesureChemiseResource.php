<?php

namespace App\Filament\Resources\MesureChemises;

use App\Filament\Resources\MesureChemises\Pages\CreateMesureChemise;
use App\Filament\Resources\MesureChemises\Pages\EditMesureChemise;
use App\Filament\Resources\MesureChemises\Pages\ListMesureChemises;
use App\Filament\Resources\MesureChemises\Pages\ViewMesureChemise;
use App\Filament\Resources\MesureChemises\Schemas\MesureChemiseForm;
use App\Filament\Resources\MesureChemises\Schemas\MesureChemiseInfolist;
use App\Filament\Resources\MesureChemises\Tables\MesureChemisesTable;
use App\Models\EtapeProduction;
use App\Models\MesureChemise;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use UnitEnum;
class MesureChemiseResource extends Resource
{
    protected static ?string $model = MesureChemise::class;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION MESURES';
    protected static ?int $navigationSort = 1;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Chemise';

    public static function form(Schema $schema): Schema
    {
        // return MesureChemiseForm::configure($schema);
        return MesureChemiseForm::configure($schema,$schema->getRecord());
    }

    public static function infolist(Schema $schema): Schema
    {
        return MesureChemiseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MesureChemisesTable::configure($table);
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
            'index' => ListMesureChemises::route('/'),
            'create' => CreateMesureChemise::route('/create'),
            'view' => ViewMesureChemise::route('/{record}'),
            'edit' => EditMesureChemise::route('/{record}/edit'),
        ];
    }
}
