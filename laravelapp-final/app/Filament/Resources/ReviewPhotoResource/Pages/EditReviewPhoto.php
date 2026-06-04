<?php

namespace App\Filament\Resources\ReviewPhotoResource\Pages;

use App\Filament\Resources\ReviewPhotoResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReviewPhoto extends EditRecord
{
    protected static string $resource = ReviewPhotoResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
