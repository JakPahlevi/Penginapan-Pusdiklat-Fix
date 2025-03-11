<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\PaymentsRelationManagerResource\RelationManagers\PaymentsRelationManager;
use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;
use App\Models\Reservation;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?int $navigationSort = 6;
    // protected static ?string $navigationGroup = 'Reservasi';


    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('code')
                ->required()
                ->unique(ignoreRecord: true)
                ->columnSpan(2)
                ->default(fn() => 'PBK-' . strtoupper(Str::random(8))),
            Select::make('customer_id')
                ->relationship('customer', 'name')
                ->required()
                ->searchable(),
            Select::make('room_id')
                ->relationship('room', 'room_type')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    if ($state && $get('duration')) {
                        $room = Room::find($state);
                        $duration = $get('duration');
                        $total = $room->price_per_day * $duration;
                        $set('total_amount', $total);
                    }
                }),
            DatePicker::make('check_in_date')
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $checkOutDate = $get('check_out_date');
                    if ($checkOutDate) {
                        $checkInDate = \Carbon\Carbon::parse($state)->startOfDay();
                        $checkOutDate = \Carbon\Carbon::parse($checkOutDate)->startOfDay();
                        $duration = $checkInDate->diffInDays($checkOutDate);
                        $set('duration', $duration);

                        // Update total amount when duration changes
                        if ($get('room_id')) {
                            $room = Room::find($get('room_id'));
                            $total = $room->price_per_day * $duration;
                            $set('total_amount', $total);
                        }
                    }
                })
                ->displayFormat('d/m/Y')
                ->native(false)
                ->reactive()
                ->required(),
            DatePicker::make('check_out_date')
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $checkInDate = $get('check_in_date');
                    if ($checkInDate) {
                        $checkInDate = \Carbon\Carbon::parse($checkInDate)->startOfDay();
                        $checkOutDate = \Carbon\Carbon::parse($state)->startOfDay();
                        $duration = $checkInDate->diffInDays($checkOutDate);
                        $set('duration', $duration);

                        // Update total amount when duration changes
                        if ($get('room_id')) {
                            $room = Room::find($get('room_id'));
                            $total = $room->price_per_day * $duration;
                            $set('total_amount', $total);
                        }
                    }
                })
                ->displayFormat('d/m/Y')
                ->native(false)
                ->reactive()
                ->required(),
            TextInput::make('duration')
                ->numeric()
                ->readOnly()
                ->required(),
            TextInput::make('total_amount')


                ->numeric()
                ->readOnly()
                ->required(),
            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'confirmed' => 'Confirmed',
                    'checked_in' => 'Checked In',
                    'checked_out' => 'Checked Out',
                    'cancelled' => 'Cancelled',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('room.room_type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_in_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_out_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'checked_in' => 'Checked In',
                        'checked_out' => 'Checked Out',
                        'cancelled' => 'Cancelled',
                    ])
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'checked_in' => 'Checked In',
                        'checked_out' => 'Checked Out',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('customer')
                    ->relationship('customer', 'name'),
                Tables\Filters\Filter::make('check_in_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ]),
                Tables\Filters\Filter::make('check_out_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    FilamentExportBulkAction::make('Export'),
                ]),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            PaymentsRelationManager::class,
        ];
    }
}
