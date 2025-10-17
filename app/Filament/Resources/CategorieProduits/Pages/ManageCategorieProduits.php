<?php

namespace App\Filament\Resources\CategorieProduits\Pages;

use App\Filament\Resources\CategorieProduits\CategorieProduitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;

class ManageCategorieProduits extends ManageRecords
{
    protected static string $resource = CategorieProduitResource::class;

    
    
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                           
                    $data['user_id'] = Auth::id();
                            return $data;
                        }),
        ];
    }

    
}
