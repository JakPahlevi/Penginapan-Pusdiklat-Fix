<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\BoardingHouseResource\Pages;
use App\Filament\Resources\BoardingHouseResource\RelationManagers;
use App\Models\BoardingHouse;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class BoardingHouseResource extends Resource
{
    protected static ?string $model = BoardingHouse::class;
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = 3;
    // protected static ?string $navigationGroup = 'Reservasi';


    public static function form(Form $form): Form
    {
        return $form->schema([
            FileUpload::make('thumbnail')
                ->required()
                ->image()
                ->directory('boarding-houses')
                ->columnSpan(2),
            TextInput::make('name')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn(string $state, callable $set) => $set('slug', Str::slug($state))),
            TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true),
            Select::make('city_id')
                ->relationship('city', 'name')
                ->required(),
            Select::make('category_id')
                ->relationship('category', 'name')
                ->required(),
            TextInput::make('price')
                ->numeric()
                ->required()
                ->prefix('Rp'),
            Select::make('status')
                ->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'maintenance' => 'Maintenance',
                ])
                ->required(),
            TextInput::make('address')
                ->required()
                ->columnSpanFull(),

            RichEditor::make('description')
                ->required()
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'maintenance' => 'Maintenance',
                    ])
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'maintenance' => 'Maintenance',
                    ]),
                Tables\Filters\SelectFilter::make('city')
                    ->relationship('city', 'name'),
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBoardingHouses::route('/'),
            'create' => Pages\CreateBoardingHouse::route('/create'),
            'edit' => Pages\EditBoardingHouse::route('/{record}/edit'),
        ];
    }
}
