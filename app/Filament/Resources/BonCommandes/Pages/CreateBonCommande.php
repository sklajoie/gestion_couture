<?php

namespace App\Filament\Resources\BonCommandes\Pages;

use App\Filament\Resources\BonCommandes\BonCommandeResource;
use App\Models\BonCommande;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateBonCommande extends CreateRecord
{
    protected static string $resource = BonCommandeResource::class;

        protected function mutateFormDataBeforeCreate(array $data): array
{
    $now = Carbon::now();
    $nowmonth = $now->format('m');
    $prefix = $now->format('ym'); // Année sur 2 chiffres + mois → ex: 2510

    // Compteur du jour (ex: 001, 002…)
    $countToday = BonCommande::count() + 1;
    $suffix = str_pad($countToday, 3, '0', STR_PAD_LEFT); // ex: 001

    $data['reference'] = "BC{$prefix}{$suffix}"; // ex: 2510001
    $data['user_id'] = Auth::id();
    $data['date_commande'] = date('Y-m-d');
    return $data;
}

}
