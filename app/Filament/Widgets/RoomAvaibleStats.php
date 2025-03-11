<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Facility;
use App\Models\RoomUnitNumber;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RoomAvaibleStats extends BaseWidget

{
    protected function getStats(): array
    {
        return [
            stat::make('Rooms Available', RoomUnitNumber::where('status', 'available')->count())
                ->description('The Total of Room Unit Number (Available)')
                ->icon('heroicon-s-squares-2x2')
                ->descriptionColor('success'),
            stat::make('customer', Customer::count())
                ->description('The Total of Customer')
                ->icon('heroicon-s-users')
                ->descriptionColor('success')
                ->descriptionIcon('heroicon-s-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            stat::make('Facilities', Facility::count())
                ->description('The Total of Facilities')
                ->icon('heroicon-s-sparkles')
                ->descriptionColor('success'),
        ];
    }
}
