<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'user_email',
        'user_phone',
        'address',
        'latitude',
        'longitude',
        'place_id',
        'vehicle_type',
        'booking_date',
        'booking_time',
        'package_id',
        'status',
        'notes',
        'total_price',
        'payment_method',
        'gift_card_id',
        'gift_card_discount',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'total_price' => 'decimal:2',
        'gift_card_discount' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function bookingAddons()
    {
        return $this->hasMany(BookingAddon::class);
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'booking_addons')
            ->withPivot('quantity', 'price_at_booking')
            ->withTimestamps();
    }

    public function giftCard()
    {
        return $this->belongsTo(GiftCard::class);
    }
}

