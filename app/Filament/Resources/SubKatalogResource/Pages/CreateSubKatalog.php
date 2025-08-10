<?php

namespace App\Filament\Resources\SubKatalogResource\Pages;

use App\Filament\Resources\SubKatalogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSubKatalog extends CreateRecord
{
    protected static string $resource = SubKatalogResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
