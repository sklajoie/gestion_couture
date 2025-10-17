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

    protected function mutateFormDataBeforeCreate(array $data): array
{
    $now = Carbon::now();
    $prefix = $now->format('ym'); // Année sur 2 chiffres + mois → ex: 2510

    // Compteur du jour (ex: 001, 002…)
    $countToday = Produit::count() + 1;
    $suffix = str_pad($countToday, 3, '0', STR_PAD_LEFT); // ex: 001

    $data['code_barre'] = "{$prefix}{$suffix}"; // ex: 2510001
    $data['user_id'] = Auth::id();
    return $data;
}
}
