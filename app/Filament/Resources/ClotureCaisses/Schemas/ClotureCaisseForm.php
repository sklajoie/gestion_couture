<?php

namespace App\Filament\Resources\ClotureCaisses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ClotureCaisseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('reference')
                //     ->required(),
                DatePicker::make('date')
                    ->required()
                    ->default(now()),
                // TextInput::make('montant_devis')
                //     ->numeric()
                //     ->default(0),
                // TextInput::make('montant_vente')
                //     ->numeric()
                //     ->default(0),
                // TextInput::make('montant_entre')
                //     ->numeric()
                //     ->default(0),
                // TextInput::make('montant_solde')
                //     ->numeric()
                //     ->default(0),
                // TextInput::make('agence_id')
                //     ->required()
                //     ->numeric(),
                    Hidden::make('user_id')
                       ->default(fn () => Auth::id()),
            ]);
    }
}
