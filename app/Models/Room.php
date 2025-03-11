<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'boarding_house_id',
        // 'room_number',
        'room_type',
        'square_feet',
        'price_per_day',
        'is_available',
        'description',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function boardingHouse(): BelongsTo
    {
        return $this->belongsTo(BoardingHouse::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
    public function roomUnitNumbers()
    {
        return $this->hasMany(RoomUnitNumber::class);
    }
}
