<?php

namespace App\Filament\Resources\Produits\Pages;

use Illuminate\Support\Str;
use App\Filament\Resources\Produits\ProduitResource;
use App\Models\Produit;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateProduit extends CreateRecord
{
    protected static string $resource = ProduitResource::class;

//     protected function mutateFormDataBeforeCreate(array $data): array
// {

//     return $data;
// }
}
