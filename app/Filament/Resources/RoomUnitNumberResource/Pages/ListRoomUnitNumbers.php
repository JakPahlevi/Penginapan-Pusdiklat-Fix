<?php

namespace App\Filament\Resources\RoomUnitNumberResource\Pages;

use App\Filament\Resources\RoomUnitNumberResource;
use App\Models\RoomUnitNumber;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListRoomUnitNumbers extends ListRecords
{
    protected static string $resource = RoomUnitNumberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            Tab::make('All')
                ->badge(RoomUnitNumber::count()),
            Tab::make('Available')
                ->badge(RoomUnitNumber::where('status', 'available')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn(Builder $query): Builder => $query->where('status', 'available')),
            Tab::make('Occupied')
                ->badge(RoomUnitNumber::where('status', 'occupied')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn(Builder $query): Builder => $query->where('status', 'occupied')),
            Tab::make('Under Maintenance')
                ->badge(RoomUnitNumber::where('status', 'under_maintenance')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn(Builder $query): Builder => $query->where('status', 'under_maintenance')),
            Tab::make('Cleaning')
                ->badge(RoomUnitNumber::where('status', 'cleaning')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn(Builder $query): Builder => $query->where('status', 'cleaning')),
        ];
    }
}
