<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceRecord extends Model
{
    protected $fillable = [
        'payment_id',
        'user_id',
        'data',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
