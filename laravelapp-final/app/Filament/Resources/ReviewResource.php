<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 3;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $label = 'Review';

    protected static ?string $pluralLabel = 'Reviews';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                Components\Select::make('tourist_attraction_id')
                    ->relationship('touristAttraction', 'name')
                    ->required()
                    ->searchable(),
                Components\Select::make('booking_id')
                    ->relationship('booking', 'booking_code')
                    ->required()
                    ->searchable(),
                Components\Select::make('rating')
                    ->options([
                        1 => '1 Star',
                        2 => '2 Stars',
                        3 => '3 Stars',
                        4 => '4 Stars',
                        5 => '5 Stars',
                    ])
                    ->required(),
                Components\Textarea::make('comment')
                    ->maxLength(65535),
                Components\Toggle::make('is_verified')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Nama User')->searchable(),
                Tables\Columns\TextColumn::make('touristAttraction.name')->label('Wisata')->searchable(),
                Tables\Columns\TextColumn::make('rating')->label('Rating'),
                Tables\Columns\TextColumn::make('comment')->label('Komentar')->limit(30),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal')->dateTime(),
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
            RelationManagers\ReviewPhotosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
