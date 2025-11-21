<?php

namespace App\Filament\Resources\ClotureAteliers;

use App\Filament\Resources\ClotureAteliers\Pages\ManageClotureAteliers;
use App\Models\ClotureAtelier;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use UnitEnum;

class ClotureAtelierResource extends Resource
{
    protected static ?string $model = ClotureAtelier::class;

        protected static string | UnitEnum | null $navigationGroup = 'GESTION ATELIER';
    protected static ?int $navigationSort = 2;
    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-archive-box-arrow-down";

    protected static ?string $recordTitleAttribute = 'reference';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('reference')
                //     ->required(),
               
                // TextInput::make('montant_total')
                //     ->required()
                //     ->numeric(),
                
                Select::make('atelier_id')
                    ->relationship('atelier', 'nom')
                    ->label('Atelier')
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->preload()
                    ->default(fn () => Auth::user()->employe->atelier_id),
                    // ->hidden(fn () => Auth::user()->employe->atelier_id),
                Select::make('employe_id')
                    // ->relationship('employer', 'nom')
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->preload()
                    ->label('Employer')
                    ->default(fn () => Auth::user()->employe_id)
                    // ->hidden(fn () => Auth::user()->employe_id)
                    ->options(function (callable $get) {
                        $atelierId = $get('atelier_id');
                    return \App\Models\Employe::where('atelier_id',$atelierId )->get()
                    ->mapWithKeys(fn ($e) => [$e->id => $e->nom . ' ' . $e->prenom])
                    ->toArray(); }),
                DatePicker::make('date')
                    ->required()
                    ->default(now())
                    ->readOnly(),
                Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('reference'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('montant_total')
                    ->numeric(),
                TextEntry::make('employe.nom')
                     ->formatStateUsing(function ($state, $record) {
                    return $record->employe
                        ? "{$record->employe->prenom} {$record->employe->nom}"
                        : '-';
                        })
                    ->placeholder('-'),
                TextEntry::make('atelier.nom')
                    ->placeholder('-'),
                TextEntry::make('user.name')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->recordTitleAttribute('reference')
            ->columns([
                TextColumn::make('date')
                    ->date('j M, Y')
                    ->sortable()
                    ->label('Date'),
                TextColumn::make('reference')
                    ->searchable()
                    ->label('Reference'),
                TextColumn::make('montant_total')
                    ->numeric()
                    ->sortable()
                    ->label('Montant'),
                TextColumn::make('employe.nom')
                    ->formatStateUsing(function ($state, $record) {
                    return $record->employe
                        ? "{$record->employe->prenom} {$record->employe->nom}"
                        : '-';
                        })
                    ->label('Employer')
                     ->searchable()
                    ->sortable(),
                TextColumn::make('atelier.nom')
                    ->sortable()
                     ->searchable()
                    ->label('Atelier'),
                TextColumn::make('user.name')
                    ->label('Utilisateur')
                     ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                Action::make('Cloture PDF')
                     ->label('Imprimer') // aucun texte affiché
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('cloture-atelier', ['reference' => $record->reference]))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
                 ])
                 ->bulkActions([
                /////impression////////
                BulkAction::make('Imprimer Cloture')
                ->icon('heroicon-o-printer')
                ->action(function (Collection $records, Component $livewire) {
                    $ids = $records->pluck('id')->toArray();
                    $url = route('cloture-atelier-group', ['cloture_ids' => $ids]);
                    
                    $livewire->js('window.open(\'' . $url . '\', \'_blank\');');
                }),

            // ->deselectRecordsAfterCompletion(),
                  DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageClotureAteliers::route('/'),
        ];
    }
}
