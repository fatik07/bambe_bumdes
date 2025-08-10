<?php

namespace App\Filament\Resources\SubKatalogResource\Pages;

use App\Filament\Resources\SubKatalogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubKatalogs extends ListRecords
{
    protected static string $resource = SubKatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
