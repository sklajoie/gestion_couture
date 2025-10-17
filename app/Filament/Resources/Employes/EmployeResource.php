<?php

namespace App\Filament\Resources\Employes;

use App\Filament\Resources\Employes\Pages\CreateEmploye;
use App\Filament\Resources\Employes\Pages\EditEmploye;
use App\Filament\Resources\Employes\Pages\ListEmployes;
use App\Filament\Resources\Employes\Pages\ViewEmploye;
use App\Filament\Resources\Employes\Schemas\EmployeForm;
use App\Filament\Resources\Employes\Schemas\EmployeInfolist;
use App\Filament\Resources\Employes\Tables\EmployesTable;
use App\Models\Employe;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EmployeResource extends Resource
{
    protected static ?string $model = Employe::class;

    protected static string | UnitEnum | null $navigationGroup = 'RESSOURCES HUMAINES';
    protected static ?int $navigationSort = 1;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return EmployeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EmployeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployesTable::configure($table);
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
            'index' => ListEmployes::route('/'),
            'create' => CreateEmploye::route('/create'),
            'view' => ViewEmploye::route('/{record}'),
            'edit' => EditEmploye::route('/{record}/edit'),
        ];
    }
}
