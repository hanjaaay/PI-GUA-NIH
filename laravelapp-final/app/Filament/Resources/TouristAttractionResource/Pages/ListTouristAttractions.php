<?php

namespace App\Filament\Resources\TouristAttractionResource\Pages;

use App\Filament\Resources\TouristAttractionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTouristAttractions extends ListRecords
{
    protected static string $resource = TouristAttractionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
