<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingAddon extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'addon_id',
        'quantity',
        'price_at_booking',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_at_booking' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }
}

