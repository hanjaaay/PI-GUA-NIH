<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketPhotoResource\Pages;
use App\Models\TicketPhoto;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TicketPhotoResource extends Resource
{
    protected static ?string $model = TicketPhoto::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Event Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $label = 'Event Gallery';

    protected static ?string $pluralLabel = 'Event Gallery';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTicketPhotos::route('/'),
            'create' => Pages\CreateTicketPhoto::route('/create'),
            'edit' => Pages\EditTicketPhoto::route('/{record}/edit'),
        ];
    }
}
