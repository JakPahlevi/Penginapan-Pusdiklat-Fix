<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\RoomUnitNumberResource\Pages;
use App\Filament\Resources\RoomUnitNumberResource\RelationManagers;
use App\Models\RoomUnitNumber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomUnitNumberResource extends Resource
{
    protected static ?string $model = RoomUnitNumber::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('boarding_house_id')
                    ->relationship('boardingHouse', 'name')
                    ->label('Boarding House') // Menghubungkan dengan model Room  
                    ->required(),
                Forms\Components\Select::make('room_id')
                    ->relationship('room', 'room_type') // Menghubungkan dengan model Room  
                    ->required(),
                Forms\Components\TextInput::make('room_number')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'available' => 'Available',
                        'occupied' => 'Occupied',
                        'under_maintenance' => 'Under Maintenance',
                        'cleaning' => 'Cleaning',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('room.room_type')
                    ->sortable()
                    ->label('Room'),

                Tables\Columns\TextColumn::make('room_number')
                    ->searchable()
                    ->label('Room Number'),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'available' => 'Available',
                        'occupied' => 'Occupied',
                        'under_maintenance' => 'Under Maintenance',
                        'cleaning' => 'Cleaning',
                    ])
                    ->label('Status'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'available' => 'Available',
                        'occupied' => 'Occupied',
                        'under_maintenance' => 'Under Maintenance',
                        'cleaning' => 'Cleaning',
                    ]),
                Tables\Filters\SelectFilter::make('room_type')
                    ->relationship('room', 'room_type')
                    ->label('Room'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoomUnitNumbers::route('/'),
            'create' => Pages\CreateRoomUnitNumber::route('/create'),
            'edit' => Pages\EditRoomUnitNumber::route('/{record}/edit'),
        ];
    }
}
