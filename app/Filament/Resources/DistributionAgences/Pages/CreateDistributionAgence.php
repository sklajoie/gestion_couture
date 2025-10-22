<?php

namespace App\Filament\Resources\DistributionAgences\Pages;

use App\Filament\Resources\DistributionAgences\DistributionAgenceResource;
use App\Models\DetailDistributionAgence;
use App\Models\DistributionAgence;
use App\Models\StockAgence;
use App\Models\StockEntreprise;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateDistributionAgence extends CreateRecord
{
    protected static string $resource = DistributionAgenceResource::class;







       
}
