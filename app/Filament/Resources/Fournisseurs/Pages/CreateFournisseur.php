<?php

namespace App\Filament\Resources\Fournisseurs\Pages;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\Fournisseurs\FournisseurResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFournisseur extends CreateRecord
{
    protected static string $resource = FournisseurResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // dd($data);
        $data['user_id'] = Auth::id();
        return $data;
    }
}
