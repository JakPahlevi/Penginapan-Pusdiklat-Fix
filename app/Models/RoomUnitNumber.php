<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomUnitNumber extends Model
{
    use HasFactory, SoftDeletes;

    // Nama tabel yang terkait dengan model ini  
    protected $table = 'room_unit_number';
    // Kolom yang dapat diisi secara massal  
    protected $fillable = [
        'boarding_house_id',
        'room_id',
        'room_number',
        'status',
    ];
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function boardingHouse(): BelongsTo
    {
        return $this->belongsTo(BoardingHouse::class);
    }
}
