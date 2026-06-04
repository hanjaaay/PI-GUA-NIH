<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use App\Support\BookingStatuses;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use PDF;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 1;

    protected static ?string $label = 'Booking';

    protected static ?string $pluralLabel = 'Bookings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Booking Information')
                    ->schema([
                        Forms\Components\TextInput::make('order_id')
                            ->label('Order ID')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\Select::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('tourist_attraction_id')
                            ->label('Tourist Attraction')
                            ->relationship('touristAttraction', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('ticket_id')
                            ->label('Ticket')
                            ->relationship('ticket', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Booking Details')
                    ->schema([
                        Forms\Components\DatePicker::make('visit_date')
                            ->label('Visit Date')
                            ->required()
                            ->native(false),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        Forms\Components\TextInput::make('total_price')
                            ->label('Total Price')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                BookingStatuses::PENDING => 'Pending',
                                BookingStatuses::PAID => 'Paid',
                                BookingStatuses::CANCELLED => 'Cancelled',
                                BookingStatuses::EXPIRED => 'Expired',
                                BookingStatuses::FAILED => 'Failed',
                                BookingStatuses::CHALLENGE => 'Challenge',
                            ])
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('midtrans_order_id')
                            ->label('Midtrans Order ID')
                            ->disabled()
                            ->dehydrated(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->label('Order ID')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Pemesan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('touristAttraction.name')
                    ->label('Wisata')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('ticket.name')
                    ->label('Tiket')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('visit_date')
                    ->label('Tanggal Kunjungan')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        BookingStatuses::PENDING => 'warning',
                        BookingStatuses::PAID => 'success',
                        BookingStatuses::CANCELLED,
                        BookingStatuses::EXPIRED,
                        BookingStatuses::FAILED => 'danger',
                        BookingStatuses::CHALLENGE => 'info',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Booking')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        BookingStatuses::PENDING => 'Pending',
                        BookingStatuses::PAID => 'Paid',
                        BookingStatuses::CANCELLED => 'Cancelled',
                        BookingStatuses::EXPIRED => 'Expired',
                        BookingStatuses::FAILED => 'Failed',
                        BookingStatuses::CHALLENGE => 'Challenge',
                    ]),
                Tables\Filters\Filter::make('visit_date')
                    ->form([
                        Forms\Components\DatePicker::make('visit_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('visit_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['visit_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('visit_date', '>=', $date),
                            )
                            ->when(
                                $data['visit_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('visit_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                // Download E-Ticket Action
                Action::make('download_ticket')
                    ->label('Download E-Ticket')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('info')
                    ->visible(fn (Booking $record): bool => $record->status === BookingStatuses::PAID)
                    ->action(function (Booking $record) {
                        $data = ['booking' => $record];
                        ini_set('memory_limit', '1024M');
                        $pdf = PDF::loadView('tickets.ticket-pdf', $data);

                        return response()->streamDownload(
                            fn () => print ($pdf->output()),
                            'ticket-'.$record->order_id.'.pdf'
                        );
                    }),

                // Download E-Receipt Action
                Action::make('download_receipt')
                    ->label('Download E-Receipt')
                    ->icon('heroicon-o-receipt-percent')
                    ->color('success')
                    ->visible(fn (Booking $record): bool => $record->status === BookingStatuses::PAID)
                    ->action(function (Booking $record) {
                        $data = ['booking' => $record];
                        ini_set('memory_limit', '1024M');
                        $pdf = PDF::loadView('receipts.show', $data);

                        return response()->streamDownload(
                            fn () => print ($pdf->output()),
                            'receipt-'.$record->order_id.'.pdf'
                        );
                    }),

                // View Details Action
                Action::make('view_details')
                    ->label('Lihat Detail')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (Booking $record): string => route('bookings.show', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    // Bulk Download E-Tickets
                    Tables\Actions\BulkAction::make('download_tickets')
                        ->label('Download E-Tickets')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('info')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $zip = new \ZipArchive;
                            $zipName = 'e-tickets-'.now()->format('Y-m-d-H-i-s').'.zip';
                            $zipPath = storage_path('app/temp/'.$zipName);

                            // Create temp directory if not exists
                            if (! file_exists(storage_path('app/temp'))) {
                                mkdir(storage_path('app/temp'), 0755, true);
                            }

                            if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
                                foreach ($records as $record) {
                                    if ($record->status === BookingStatuses::PAID) {
                                        $data = ['booking' => $record];
                                        ini_set('memory_limit', '1024M');
                                        $pdf = PDF::loadView('tickets.ticket-pdf', $data);
                                        $zip->addFromString('ticket-'.$record->order_id.'.pdf', $pdf->output());
                                    }
                                }
                                $zip->close();

                                return response()->download($zipPath)->deleteFileAfterSend(true);
                            }
                        }),

                    // Bulk Download E-Receipts
                    Tables\Actions\BulkAction::make('download_receipts')
                        ->label('Download E-Receipts')
                        ->icon('heroicon-o-receipt-percent')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $zip = new \ZipArchive;
                            $zipName = 'e-receipts-'.now()->format('Y-m-d-H-i-s').'.zip';
                            $zipPath = storage_path('app/temp/'.$zipName);

                            // Create temp directory if not exists
                            if (! file_exists(storage_path('app/temp'))) {
                                mkdir(storage_path('app/temp'), 0755, true);
                            }

                            if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
                                foreach ($records as $record) {
                                    if ($record->status === BookingStatuses::PAID) {
                                        $data = ['booking' => $record];
                                        ini_set('memory_limit', '1024M');
                                        $pdf = PDF::loadView('receipts.show', $data);
                                        $zip->addFromString('receipt-'.$record->order_id.'.pdf', $pdf->output());
                                    }
                                }
                                $zip->close();

                                return response()->download($zipPath)->deleteFileAfterSend(true);
                            }
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([]);
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
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
