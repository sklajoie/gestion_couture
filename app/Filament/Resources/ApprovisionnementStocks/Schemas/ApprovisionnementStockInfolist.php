<?php

namespace App\Filament\Resources\ApprovisionnementStocks\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ApprovisionnementStockInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('reference')
                    ->label('Référence'),
                TextEntry::make('date_operation')
                    ->label('Date de l\'opération'),
                TextEntry::make('total_appro')
                    ->label('Montant Total'),
                RepeatableEntry::make('detailApproStocks')
                 ->columnSpanFull()
                     ->table([
                            TableColumn::make('TYPE'),
                            TableColumn::make('REFERENCE'),
                            TableColumn::make('QTE'),
                            TableColumn::make('PRIX'),
                            TableColumn::make('TOTAL'),
                        ])
                    ->schema([
                        TextEntry::make('mesure_type')
                            ->label('Type'),
                        TextEntry::make('reference')
                            ->label('Référence'),
                        TextEntry::make('quantite')
                            ->label('Quantité'),
                        TextEntry::make('prix_unitaire')
                            ->label('Prix'),
                        TextEntry::make('total')
                            ->label('Total'),
                        
                    ])->columns(5),
            ]);
    }
}
