<?php

namespace App\Filament\Resources\ReviewPhotoResource\Pages;

use App\Filament\Resources\ReviewPhotoResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReviewPhotos extends ListRecords
{
    protected static string $resource = ReviewPhotoResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
