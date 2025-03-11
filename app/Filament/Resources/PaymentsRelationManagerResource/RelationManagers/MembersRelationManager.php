<?php

namespace App\Filament\Resources\PaymentsRelationManagerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payment';

    public function form(Form $form): Form
    {
        return $form->schema([
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
                ->required(),
            FileUpload::make('proof_of_payment')
                ->image()
                ->directory('payment-proofs'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('payment_method'),
                Tables\Columns\TextColumn::make('payment_status'),
                Tables\Columns\TextColumn::make('payment_date')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('idr'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
