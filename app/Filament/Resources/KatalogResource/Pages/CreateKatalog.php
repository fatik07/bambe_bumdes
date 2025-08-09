<?php

namespace App\Filament\Resources\KatalogResource\Pages;

use App\Filament\Resources\KatalogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKatalog extends CreateRecord
{
    protected static string $resource = KatalogResource::class;
    
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
