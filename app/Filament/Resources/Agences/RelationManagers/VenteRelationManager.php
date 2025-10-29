<?php

namespace App\Filament\Resources\Agences\RelationManagers;

use App\Filament\Resources\Ventes\VenteResource;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class VenteRelationManager extends RelationManager
{
    protected static string $relationship = 'vente';

    // protected static ?string $relatedResource = VenteResource::class;

    public function table(Table $table): Table
    {
   
        
        return VenteResource::table($table)
            ->headerActions([
                CreateAction::make(),
            ]);
    }

        public function form(Schema $schema): Schema
    {
        
        return VenteResource::form($schema);
    }
    

    public function isReadOnly(): bool
    {
        return false;
    }
}
