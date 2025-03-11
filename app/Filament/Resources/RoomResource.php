<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\RoomResource\Pages;
use App\Filament\Resources\RoomResource\RelationManagers;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?int $navigationSort = 4;
    // protected static ?string $navigationGroup = 'Reservasi';


    public static function form(Form $form): Form
    {
        return $form->schema([
            FileUpload::make('images')
                ->multiple()
                ->image()
                ->directory('room-images')
                ->required()
                ->saveRelationshipsUsing(function ($component, $state, $record) {
                    Log::info('Uploaded images:', $state); // Tambahkan ini untuk debugging  
                    // Hapus gambar yang ada jika diperlukan  
                    $record->images()->delete();

                    // Simpan jalur file yang diunggah ke relasi 'images'  
                    foreach ($state as $imagePath) {
                        $record->images()->create(['image' => $imagePath]);
                    }
                })
                ->columnSpan(2),
            Select::make('boarding_house_id')
                ->relationship('boardingHouse', 'name')
                ->required(),
            // Select::make('room_number')
            //     ->options(function () {
            //         $options = [];
            //         for ($floor = 1; $floor <= 3; $floor++) { // 3 lantai
            //             for ($room = 1; $room <= 8; $room++) { // 8 kamar per lantai
            //                 $roomNumber = sprintf('%02d%02d', $floor, $room);
            //                 $options[$roomNumber] = "Lantai $floor - $roomNumber";
            //             }
            //         }
            //         return $options;
            //     })
            //     ->required()
            //     ->searchable(),

            Select::make('room_type')
                ->options([
                    'Standard Room' => 'Standard Room',
                    'Superior Room' => 'Superior Room',
                    'Deluxe Room' => 'Deluxe Room',
                ])
                ->required(),
            TextInput::make('square_feet')
                ->numeric()
                ->required()
                ->prefix('m²'),
            TextInput::make('price_per_day')
                ->numeric()
                ->required()
                ->prefix('Rp'),
            Toggle::make('is_available')
                ->default(true),
            RichEditor::make('description')
                ->columnSpanFull(),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('boardingHouse.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('room_type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_per_day')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_available')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('boarding_house')
                    ->relationship('boardingHouse', 'name'),
                Tables\Filters\TernaryFilter::make('is_available'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
