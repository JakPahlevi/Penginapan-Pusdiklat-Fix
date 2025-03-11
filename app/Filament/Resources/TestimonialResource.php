<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\TestimonialResource\Pages;
use App\Filament\Resources\TestimonialResource\RelationManagers;
use App\Models\Testimonial;
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

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?int $navigationSort = 9;
    // protected static ?string $navigationGroup = 'Reservasi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            FileUpload::make('photo')
                ->image()
                ->required()
                ->directory('testimonials')
                ->columnSpan(2),
            Select::make('boarding_house_id')
                ->relationship('boardingHouse', 'name')
                ->required()
                ->columnSpan(2),
            RichEditor::make('content')
                ->required()
                ->columnSpanFull(),
            TextInput::make('rating')
                ->numeric()
                ->required()
                ->minValue(1)
                ->maxValue(5),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('boardingHouse.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('photo'),
                Tables\Columns\TextColumn::make('rating')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('boarding_house')
                    ->relationship('boardingHouse', 'name'),
                Tables\Filters\SelectFilter::make('rating')
                    ->options([
                        '1' => '1 Star',
                        '2' => '2 Stars',
                        '3' => '3 Stars',
                        '4' => '4 Stars',
                        '5' => '5 Stars',
                    ]),
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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
