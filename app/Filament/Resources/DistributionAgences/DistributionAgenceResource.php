<?php

namespace App\Filament\Resources\DistributionAgences;

use App\Filament\Resources\DistributionAgences\Pages\CreateDistributionAgence;
use App\Filament\Resources\DistributionAgences\Pages\EditDistributionAgence;
use App\Filament\Resources\DistributionAgences\Pages\ListDistributionAgences;
use App\Filament\Resources\DistributionAgences\Pages\ViewDistributionAgence;
use App\Filament\Resources\DistributionAgences\Schemas\DistributionAgenceForm;
use App\Filament\Resources\DistributionAgences\Schemas\DistributionAgenceInfolist;
use App\Filament\Resources\DistributionAgences\Tables\DistributionAgencesTable;
use App\Models\DistributionAgence;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DistributionAgenceResource extends Resource
{
    protected static ?string $model = DistributionAgence::class;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION AGENCES';
    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'reference';

    public static function form(Schema $schema): Schema
    {
        return DistributionAgenceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DistributionAgenceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DistributionAgencesTable::configure($table);
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
            'index' => ListDistributionAgences::route('/'),
            'create' => CreateDistributionAgence::route('/create'),
            'view' => ViewDistributionAgence::route('/{record}'),
            'edit' => EditDistributionAgence::route('/{record}/edit'),
        ];
    }
}
