<?php

namespace App\Filament\Resources\EtapeMesures\Pages;

use App\Filament\Resources\EtapeMesures\EtapeMesureResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
class CreateEtapeMesure extends CreateRecord
{
    //  use HasWizard;
    protected static string $resource = EtapeMesureResource::class;
}
