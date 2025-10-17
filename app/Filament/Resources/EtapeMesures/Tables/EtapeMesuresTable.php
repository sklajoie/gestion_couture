<?php

namespace App\Filament\Resources\EtapeMesures\Tables;

use App\Models\EtapeMesure;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class EtapeMesuresTable
{
    public static function configure(Table $table): Table
    {
        return $table
             ->columns([
            TextColumn::make('etapeProduction.nom')->label('Étape'),
            TextColumn::make('mesureChemise.Reference')->label('Référence chemise'),
            TextColumn::make('responsable.name')->label('Responsable'),
            ToggleColumn::make('is_completed')->label('Terminée')->sortable(),
            TextColumn::make('comments')->label('Commentaires')->limit(50),
        ])
            ->filters([
                SelectFilter::make('responsable_id')
                ->label('Filtrer par responsable')
                ->options(User::pluck('name', 'id')),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                ])
                ->toolbarActions([
                Action::make('Valider toutes les étapes')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function () {
                            EtapeMesure::where('is_completed', false)
                                ->where('responsable_id', Auth::id())
                                ->update(['is_completed' => true]);
                        }),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
