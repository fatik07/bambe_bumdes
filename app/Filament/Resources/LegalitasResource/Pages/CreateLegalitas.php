<?php

namespace App\Filament\Resources\LegalitasResource\Pages;

use App\Filament\Resources\LegalitasResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLegalitas extends CreateRecord
{
    protected static string $resource = LegalitasResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
