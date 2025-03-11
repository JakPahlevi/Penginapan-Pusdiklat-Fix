<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Models\Payment;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'pending' => Tab::make('Pending')
                ->badge(Payment::where('payment_status', 'pending')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn(Builder $query): Builder => $query->where('payment_status', 'pending')),
            'paid' => Tab::make('Paid')
                ->badgeColor('success')
                ->badge(Payment::where('payment_status', 'paid')->count())
                ->modifyQueryUsing(fn(Builder $query): Builder => $query->where('payment_status', 'paid')),
            'failed' => Tab::make('Failed')
                ->badgeColor('danger')
                ->badge(Payment::where('payment_status', 'failed')->count())
                ->modifyQueryUsing(fn(Builder $query): Builder => $query->where('payment_status', 'failed')),
            'expired' => Tab::make('Expired')
                ->badgeColor('gray')
                ->badge(Payment::where('payment_status', 'expired')->count())
                ->modifyQueryUsing(fn(Builder $query): Builder => $query->where('payment_status', 'expired')),
        ];
    }
}
