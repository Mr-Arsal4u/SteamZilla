<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCardTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'gift_card_id',
        'type',
        'amount',
        'discount_amount',
        'final_paid_amount',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_paid_amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function giftCard()
    {
        return $this->belongsTo(GiftCard::class);
    }
}
