<?php

namespace App\Filament\Resources\Versements\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class VersementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                    Section::make('PAIEMENT')
                         ->schema([
                            Select::make('vente_id')
                                ->label('Référence Vente')
                                ->relationship('vente', 'reference')
                                ->searchable()
                                ->required()
                                 ->preload()
                                ->columnSpan(2),
                           TextInput::make('montant')
                                ->label('Montant')
                                ->numeric()
                                ->columnSpan(1),
                            Select::make('mode_paiement')
                                ->label('Mode de Paiement')
                                ->columnSpan(1)
                                ->options([
                                    'Especes' => 'Espèces',
                                    'Mobile Money' => 'Mobile Money',
                                    'Wave' => 'Wave',
                                    'Virement Bancaire' => 'Virement Bancaire',
                                    'Cheque' => 'Chèque',
                                    'Autre' => 'Autre',
                                ])
                                ->default('Especes'),
                            TextInput::make('detail')
                                ->label('Detail Versement')
                                 ->columnSpan(2),
                            // Hidden::make('agence_id')
                            //     ->default(fn () => Auth::user()->agence_id),
                            Hidden::make('user_id')
                                ->default(fn () => Auth::id()),
                        
                    ])
                     ->columnSpan(4)
                    ->columnSpanFull(),
            ]);
    }
}
