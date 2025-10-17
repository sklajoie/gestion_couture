<?php

namespace App\Filament\Resources\Fournisseurs\Schemas;
use Filament\Infolists;
use Filament\Schemas\Schema;

class FournisseurInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Infolists\Components\TextEntry::make('nom'),
                Infolists\Components\TextEntry::make('contact'),
                Infolists\Components\TextEntry::make('telephone'),
                Infolists\Components\TextEntry::make('adresse'),
                Infolists\Components\TextEntry::make('email'),
            ]);
    }
}
