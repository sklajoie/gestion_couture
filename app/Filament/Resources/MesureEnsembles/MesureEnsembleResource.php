<?php

namespace App\Filament\Resources\MesureEnsembles;

use App\Filament\Resources\MesureEnsembles\Pages\CreateMesureEnsemble;
use App\Filament\Resources\MesureEnsembles\Pages\EditMesureEnsemble;
use App\Filament\Resources\MesureEnsembles\Pages\ListMesureEnsembles;
use App\Filament\Resources\MesureEnsembles\Pages\ViewMesureEnsemble;
use App\Filament\Resources\MesureEnsembles\Schemas\MesureEnsembleForm;
use App\Filament\Resources\MesureEnsembles\Schemas\MesureEnsembleInfolist;
use App\Filament\Resources\MesureEnsembles\Tables\MesureEnsemblesTable;
use App\Models\MesureEnsemble;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
class MesureEnsembleResource extends Resource
{
    protected static ?string $model = MesureEnsemble::class;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION MESURES';
    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-cube-transparent";

    protected static ?string $recordTitleAttribute = 'Type';

    public static function form(Schema $schema): Schema
    {
        // return MesureEnsembleForm::configure($schema);
        return MesureEnsembleForm::configure($schema,$schema->getRecord());
    }

    public static function infolist(Schema $schema): Schema
    {
        return MesureEnsembleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MesureEnsemblesTable::configure($table);
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
            'index' => ListMesureEnsembles::route('/'),
            'create' => CreateMesureEnsemble::route('/create'),
            'view' => ViewMesureEnsemble::route('/{record}'),
            'edit' => EditMesureEnsemble::route('/{record}/edit'),
        ];
    }
}
