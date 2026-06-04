<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Models\Ticket;
use App\Models\TouristAttraction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TicketResource extends Resource
{
    protected static ?string $model =
        Ticket::class;

    protected static ?string $navigationIcon =
        'heroicon-o-ticket';

    protected static ?string $navigationGroup =
        'Event Management';

    protected static ?string $label =
        'Ticket';

    protected static ?string $pluralLabel =
        'Tickets';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Ticket Information')

                    ->schema([

                        Select::make('tourist_attraction_id')

                            ->label('Event')

                            ->options(
                                TouristAttraction::query()
                                    ->pluck('name', 'id')
                            )

                            ->searchable()

                            ->required(),

                        TextInput::make('name')

                            ->label('Ticket Name')

                            ->required(),

                        Select::make('ticket_type')
                            ->label('Ticket Type')
                            ->options([

                                'day_pass' => 'Day Pass',

                                'full_pass' => 'Full Pass',
                            ])
                            ->live()
                            ->required(),

                        DatePicker::make('valid_date')

                            ->label('Valid Event Date')

                            ->visible(fn ($get) => $get('ticket_type') === 'day_pass'
                            )

                            ->required(fn ($get) => $get('ticket_type') === 'day_pass'
                            )

                            ->dehydrated(fn ($get) => $get('ticket_type') === 'day_pass'
                            ),

                        DatePicker::make('valid_from')

                            ->label('Valid From')

                            ->visible(fn ($get) => $get('ticket_type') === 'full_pass'
                            )

                            ->required(fn ($get) => $get('ticket_type') === 'full_pass'
                            )

                            ->dehydrated(fn ($get) => $get('ticket_type') === 'full_pass'
                            ),

                        DatePicker::make('valid_until')

                            ->label('Valid Until')

                            ->visible(fn ($get) => $get('ticket_type') === 'full_pass'
                            )

                            ->required(fn ($get) => $get('ticket_type') === 'full_pass'
                            )

                            ->dehydrated(fn ($get) => $get('ticket_type') === 'full_pass'
                            ),

                        TextInput::make('type')

                            ->label('Internal Code')

                            ->placeholder('VIP / D1 / D2')

                            ->required(),

                        TextInput::make('price')

                            ->numeric()

                            ->prefix('IDR')

                            ->required(),

                        TextInput::make('quota')

                            ->numeric()

                            ->required(),

                        TextInput::make('available_quantity')

                            ->label('Available Stock')

                            ->numeric()

                            ->required(),

                        Textarea::make('description')

                            ->rows(4),

                        Toggle::make('is_active')

                            ->default(true),

                    ])

                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make(
                    'touristAttraction.name'
                )
                    ->label('Event')
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Ticket Name')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('ticket_type')

                    ->label('Ticket Type')

                    ->colors([

                        'success' => 'full_pass',

                        'warning' => 'day_pass',
                    ]),

                Tables\Columns\TextColumn::make('type')

                    ->label('Code')

                    ->badge(),

                Tables\Columns\TextColumn::make('valid_date')

                    ->label('Day Pass')

                    ->date(),

                Tables\Columns\TextColumn::make('valid_from')

                    ->label('Valid From')

                    ->date(),

                Tables\Columns\TextColumn::make('valid_until')

                    ->label('Valid Until')

                    ->date(),

                Tables\Columns\TextColumn::make('price')

                    ->money('IDR'),

                Tables\Columns\TextColumn::make(
                    'available_quantity'
                )
                    ->label('Stock'),

                Tables\Columns\IconColumn::make(
                    'is_active'
                )
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

            'index' => Pages\ListTickets::route('/'),

            'create' => Pages\CreateTicket::route('/create'),

            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
