<?php

namespace App\Filament\Resources\RoomUnitNumberResource\Pages;

use App\Filament\Resources\RoomUnitNumberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRoomUnitNumber extends EditRecord
{
    protected static string $resource = RoomUnitNumberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
