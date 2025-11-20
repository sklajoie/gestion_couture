<?php

namespace App\Filament\Resources\StockAgences\Pages;

use App\Filament\Resources\StockAgences\StockAgenceResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Auth;

class ManageStockAgences extends ManageRecords
{
    protected static string $resource = StockAgenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),

                Action::make('INVENTAIRE')
                    ->label('Inventaire')
                    ->modalHeading('Choisir la période')
                    ->modalButton('Valider')
                    ->schema([
                    Select::make('agence_id')
                            ->preload()
                            ->options(function () {
                                return \App\Models\Agence::all()->pluck('nom', 'id');
                            })
                            ->default(Auth::user()->employe->agence_id)
                            ->label('Agence'),

                        Grid::make(2)->schema([
                            DatePicker::make('dateDebut')
                                ->label('Date début')
                                ->required(),

                            DatePicker::make('dateFin')
                                ->label('Date fin')
                                ->required(),
                        ]),
                   

                    ])
                    ->action(function (array $data) {
                            $url = route('valider-inventaire', [
                                'date_debut' => $data['dateDebut'],
                                'date_fin'   => $data['dateFin'],
                                'agence_id'  => $data['agence_id'],
                            ]);

                            // renvoyer une redirection JS qui ouvre dans un nouvel onglet
                            return \Filament\Notifications\Notification::make()
                                ->title('Ouverture Inventaire')
                                ->body("Cliquez ici pour ouvrir l’inventaire")
                                ->actions([
                                    Action::make('Ouvrir')
                                        ->url($url, shouldOpenInNewTab: true),
                                ])
                                ->duration(300000) 
                                ->send();
                        }),

        ];
    }
}
