<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction as ActionsFilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\FilamentExport;
use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use App\Models\Reservation;
use Filament\Forms;
use Barryvdh\DomPDF\PDF;
use Barryvdh\Snappy\PdfWrapper;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\DateFilter;
use Filament\Tables\Actions\FilamentExportHeaderAction;


class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?int $navigationSort = 7;
    // protected static ?string $navigationGroup = 'Reservasi';


    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('reservation_id')
                ->relationship('reservation', 'code')
                ->afterStateUpdated(function (callable $get, callable $set) {
                    $code = $get('reservation_id');
                    $reservation = Reservation::find($code);

                    if ($reservation) {
                        $total = $reservation->total_amount;
                        $set('amount', $total);
                    }
                })
                ->searchable()
                ->reactive()
                ->required(),
            Select::make('payment_method')
                ->options([
                    'transfer' => 'Transfer',
                    'cash' => 'Cash',
                    'credit_card' => 'Credit Card',
                ])
                ->required(),
            Select::make('payment_status')
                ->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'failed' => 'Failed',
                    'expired' => 'Expired',
                ])
                ->required(),
            DateTimePicker::make('payment_date')
                ->required(),
            TextInput::make('amount')
                ->numeric()
                ->required()
                ->prefix('Rp'),
            FileUpload::make('proof_of_payment')
                ->image()
                ->directory('payment-proofs'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reservation.code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'transfer' => 'success',
                        'cash' => 'warning',
                        'credit_card' => 'info',
                    }),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed' => 'danger',
                        'expired' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('payment_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('idr')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'transfer' => 'Transfer',
                        'cash' => 'Cash',
                        'credit_card' => 'Credit Card',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'expired' => 'Expired',
                    ]),
                Tables\Filters\Filter::make('payment_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Payment Date From')
                            ->native(false),
                        Forms\Components\DatePicker::make('until')
                            ->label('Payment Date Until')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('payment_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('payment_date', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('view_Invoice')
                    ->label(__('View Invoice'))
                    ->icon('heroicon-s-document-text')
                    ->url(fn($record) => self::getUrl("invoice", ['record' => $record->id])),


            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    FilamentExportBulkAction::make('Export'),
                ]),
            ])
            ->headerActions([
                ActionsFilamentExportHeaderAction::make('export')
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
            'invoice' => Pages\Invoice::route('/{record}/invoice'),
        ];
    }
}
