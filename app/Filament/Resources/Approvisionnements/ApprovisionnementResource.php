<?php

namespace App\Filament\Resources\Approvisionnements;

use App\Filament\Resources\Approvisionnements\Pages\CreateApprovisionnement;
use App\Filament\Resources\Approvisionnements\Pages\EditApprovisionnement;
use App\Filament\Resources\Approvisionnements\Pages\ListApprovisionnements;
use App\Filament\Resources\Approvisionnements\Pages\ViewApprovisionnement;
use App\Filament\Resources\Approvisionnements\Schemas\ApprovisionnementForm;
use App\Filament\Resources\Approvisionnements\Schemas\ApprovisionnementInfolist;
use App\Filament\Resources\Approvisionnements\Tables\ApprovisionnementsTable;
use App\Models\Approvisionnement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ApprovisionnementResource extends Resource
{
    protected static ?string $model = Approvisionnement::class;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION STOCK';
    protected static ?int $navigationSort = 5;

    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-arrow-path-rounded-square";

    protected static ?string $recordTitleAttribute = 'reference';

    public static function form(Schema $schema): Schema
    {
        return ApprovisionnementForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ApprovisionnementInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApprovisionnementsTable::configure($table);
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
            'index' => ListApprovisionnements::route('/'),
            'create' => CreateApprovisionnement::route('/create'),
            'view' => ViewApprovisionnement::route('/{record}'),
            'edit' => EditApprovisionnement::route('/{record}/edit'),
        ];
    }
}
