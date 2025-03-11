<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'slug',
    ];

    public function boardingHouses(): HasMany
    {
        return $this->hasMany(BoardingHouse::class);
    }
}
