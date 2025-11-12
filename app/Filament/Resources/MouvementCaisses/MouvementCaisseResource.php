<?php

namespace App\Filament\Resources\MouvementCaisses;

use App\Filament\Resources\MouvementCaisses\Pages\CreateMouvementCaisse;
use App\Filament\Resources\MouvementCaisses\Pages\EditMouvementCaisse;
use App\Filament\Resources\MouvementCaisses\Pages\ListMouvementCaisses;
use App\Filament\Resources\MouvementCaisses\Pages\ViewMouvementCaisse;
use App\Filament\Resources\MouvementCaisses\Schemas\MouvementCaisseForm;
use App\Filament\Resources\MouvementCaisses\Schemas\MouvementCaisseInfolist;
use App\Filament\Resources\MouvementCaisses\Tables\MouvementCaissesTable;
use App\Models\MouvementCaisse;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Schema\Builder;
use UnitEnum;

class MouvementCaisseResource extends Resource
{
    protected static ?string $model = MouvementCaisse::class;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION CAISSE';
    protected static ?int $navigationSort = 3;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'reference';

    public static function form(Schema $schema): Schema
    {
        return MouvementCaisseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        
        return MouvementCaisseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MouvementCaissesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
// public static function getEloquentQuery(): EloquentBuilder
// {
//     return parent::getEloquentQuery()->with('structure');
// }

 

    
    public static function getPages(): array
    {
        return [
            'index' => ListMouvementCaisses::route('/'),
            'create' => CreateMouvementCaisse::route('/create'),
            'view' => ViewMouvementCaisse::route('/{record}'),
            'edit' => EditMouvementCaisse::route('/{record}/edit'),
        ];
    }
}
