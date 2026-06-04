<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TouristAttractionResource\Pages;
use App\Models\TouristAttraction;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TouristAttractionResource extends Resource
{
    protected static ?string $model =
        TouristAttraction::class;

    protected static ?string $navigationIcon =
        'heroicon-o-ticket';

    protected static ?string $navigationGroup =
        'Event Management';

    protected static ?string $label =
        'Event';

    protected static ?string $pluralLabel =
        'Events';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Event Information')

                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->rows(5),

                        Forms\Components\TextInput::make('location')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('city')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('province')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('venue_name')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('organizer')
                            ->maxLength(255),

                        Forms\Components\DatePicker::make('start_date')
                            ->required(),

                        Forms\Components\DatePicker::make('end_date')
                            ->required(),

                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('IDR')
                            ->required(),

                        Forms\Components\Textarea::make('event_rules')
                            ->rows(6),

                        FileUpload::make('featured_image')
    ->label('Event Banner')
    ->image()
    ->disk('public')
    ->directory('tourist-attractions')
    ->visibility('public')
    ->saveUploadedFileUsing(function ($file) {
        return $file->store('tourist-attractions', 'public');
    })
    ->columnSpanFull(),

FileUpload::make('gallery')
    ->label('Gallery')
    ->image()
    ->multiple()
    ->disk('public')
    ->directory('tourist-attractions/gallery')
    ->visibility('public')
    ->saveUploadedFileUsing(function ($file) {
        return $file->store('tourist-attractions/gallery', 'public');
    })
    ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true),

                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Banner'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('venue_name'),

                Tables\Columns\TextColumn::make('start_date')
                    ->date(),

                Tables\Columns\TextColumn::make('end_date')
                    ->date(),

                Tables\Columns\TextColumn::make('price')
                    ->money('IDR'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [

            'index' => Pages\ListTouristAttractions::route('/'),

            'create' => Pages\CreateTouristAttraction::route('/create'),

            'edit' => Pages\EditTouristAttraction::route('/{record}/edit'),
        ];
    }
}
