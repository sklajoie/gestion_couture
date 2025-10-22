<?php

namespace App\Filament\Resources\Entreprises;

use App\Filament\Resources\Entreprises\Pages\ManageEntreprises;
use App\Models\Entreprise;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class EntrepriseResource extends Resource
{
    protected static ?string $model = Entreprise::class;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION ENTREPRISES';
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->required()
                    ->label('Nom Entreprise'),
                TextInput::make('telephone')
                    ->required()
                    ->label('Téléphone'),
                TextInput::make('contact')
                    ->label('Contact'),
                TextInput::make('email')
                    ->label('Email')        
                    ->email(),
                TextInput::make('adresse')
                        ->label('Adresse'),
                TextInput::make('ville')
                        ->label('Ville'),
                FileUpload::make('logo')
                    ->label('Logo')
                    ->disk('public')
                    ->directory('Logo')
                    ->visibility('public')
                    ->image()
                    ->imagePreviewHeight('150')
                    ->enableDownload()
                    ->enableOpen()
                    ->openable()
                    ->previewable(),
                TextInput::make('pied_page')
                    ->label('Pied de page'),
                TextInput::make('site_web')
                    ->label('Site Web'),
                Checkbox::make('status')
                    ->label('Status')
                    ->default(true), 
                Hidden::make('user_id')
                    ->default(fn () => Auth::id()),   
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nom'),
                TextEntry::make('telephone'),
                TextEntry::make('contact'),
                TextEntry::make('email'),
                TextEntry::make('adresse'),
                TextEntry::make('ville'),
                ImageEntry::make('logo')
                    ->disk('public')
                    ->imageWidth(200)
                    ->imageHeight(200)
                    ->square()
                    ->url(fn ($state) => asset('storage/' . $state))
                    ->openUrlInNewTab(),
                TextEntry::make('pied_page'),
                TextEntry::make('site_web'),
                IconEntry::make('status')->boolean(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nom')
            ->columns([
                TextColumn::make('nom')
                    ->searchable(),
                TextColumn::make('telephone'),
                TextColumn::make('contact'),
                TextColumn::make('email'), 
                TextColumn::make('adresse'),
                TextColumn::make('ville'),
                IconColumn::make('status')->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEntreprises::route('/'),
        ];
    }
}
