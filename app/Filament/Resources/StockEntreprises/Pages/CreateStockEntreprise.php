<?php

namespace App\Filament\Resources\StockEntreprises\Pages;

use App\Filament\Resources\StockEntreprises\StockEntrepriseResource;
use App\Models\StockEntreprise;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;

class CreateStockEntreprise extends CreateRecord
{
    protected static string $resource = StockEntrepriseResource::class;

    
        protected function mutateFormDataBeforeCreate(array $data): array
    {
       $now = Carbon::now();
        $prefix = $now->format('ym'); // ex: "2510"

        do {
            $code = random_int(1000, 9999); // 4 chiffres aléatoires
            $fullCode = "{$prefix}{$code}";
            $exists = StockEntreprise::where('code_barre', $fullCode)->exists();
        } while ($exists);

        $data['code_barre'] = $fullCode;

        return $data;

    }
}
