<?php

namespace App\Filament\Resources\Agences\RelationManagers;

use App\Filament\Resources\Devis\DevisResource;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;

class DevisRelationManager extends RelationManager
{
    protected static string $relationship = 'devis';

    // protected static ?string $relatedResource = DevisResource::class;

    public function table(Table $table): Table
    {
      
        return DevisResource::table($table)

        
                    ->headerActions([
                        CreateAction::make()
                    ]);

    }


public function form(Schema $schema): Schema
{
    
    return DevisResource::form($schema);
}


    public function isReadOnly(): bool
    {
        return false;
    }




   
}
