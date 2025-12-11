<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'gift_card_number',
        'pin',
        'amount',
        'original_purchase_amount',
        'discount_applied',
        'sender_name',
        'sender_email',
        'recipient_name',
        'recipient_email',
        'recipient_phone',
        'delivery_method',
        'delivery_datetime',
        'message',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'original_purchase_amount' => 'decimal:2',
        'discount_applied' => 'decimal:2',
        'delivery_datetime' => 'datetime',
        'expires_at' => 'date',
    ];

    public function transactions()
    {
        return $this->hasMany(GiftCardTransaction::class);
    }

    /**
     * Generate a unique gift card number
     */
    public static function generateCardNumber(): string
    {
        do {
            $number = 'SZGC-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 4)) . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 5));
        } while (self::where('gift_card_number', $number)->exists());

        return $number;
    }

    /**
     * Generate a PIN
     */
    public static function generatePIN(): string
    {
        return str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate discount
     */
    public static function calculateDiscount($amount): array
    {
        $discount = 0;
        if ($amount >= 100) {
            $discount = $amount * 0.10;
        }
        $finalAmount = $amount - $discount;

        return [
            'original' => $amount,
            'discount' => $discount,
            'final' => $finalAmount,
        ];
    }
}
