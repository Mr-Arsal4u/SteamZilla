<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'category',
        'has_quantity',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'has_quantity' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function bookingAddons()
    {
        return $this->hasMany(BookingAddon::class);
    }
}

