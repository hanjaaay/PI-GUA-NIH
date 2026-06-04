<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewPhotoResource\Pages;
use App\Models\ReviewPhoto;
use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewPhotoResource extends Resource
{
    protected static ?string $model = ReviewPhoto::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 4;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $label = 'Foto Review';

    protected static ?string $pluralLabel = 'Foto Review';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Select::make('review_id')
                    ->relationship('review', 'id')
                    ->required()
                    ->searchable(),
                Components\FileUpload::make('photo_path')
                    ->image()
                    ->required()
                    ->directory('review-photos'),
                Components\TextInput::make('caption')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('review.user.name')->label('Nama User')->searchable(),
                Tables\Columns\TextColumn::make('review.touristAttraction.name')->label('Wisata')->searchable(),
                Tables\Columns\ImageColumn::make('photo_path')->label('Foto'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviewPhotos::route('/'),
            'create' => Pages\CreateReviewPhoto::route('/create'),
            'edit' => Pages\EditReviewPhoto::route('/{record}/edit'),
        ];
    }
}
