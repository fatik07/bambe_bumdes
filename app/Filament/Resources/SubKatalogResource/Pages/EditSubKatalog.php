<?php

namespace App\Filament\Resources\SubKatalogResource\Pages;

use App\Filament\Resources\SubKatalogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubKatalog extends EditRecord
{
    protected static string $resource = SubKatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
