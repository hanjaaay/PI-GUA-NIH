<?php

namespace App\Filament\Resources\ReviewResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewPhotosRelationManager extends RelationManager
{
    protected static string $relationship = 'reviewPhotos';

    protected static ?string $recordTitleAttribute = 'caption';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('photo_path')
                    ->image()
                    ->required()
                    ->directory('review-photos'),
                Forms\Components\TextInput::make('caption')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo_path')
                    ->square(),
                Tables\Columns\TextColumn::make('caption'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
